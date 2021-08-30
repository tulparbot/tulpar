<?php


namespace App\Tulpar\Events\Message;


use App\Enums\CommandValidation;
use App\Models\AutoResponse;
use App\Models\ChannelRestrict;
use App\Models\CustomCommand;
use App\Models\Rank;
use App\Models\UserRank;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Contracts\FilterInterface;
use App\Tulpar\Helpers;
use App\Tulpar\Logger;
use App\Tulpar\Tulpar;
use Discord\Discord;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Message;
use Discord\Parts\Guild\Guild;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use React\Promise\Promise;

class CreateEvent
{
    /**
     * @var array
     */
    private static array $commandHistory = [];

    /**
     * @var array $history
     */
    public static array $history = [];

    /**
     * @param bool $force
     */
    public static function flushCommandHistory(bool $force = false): void
    {
        if ($force) {
            static::$history = [];
            return;
        }

        $history = [];
        foreach (static::$history as $guild => $messages) {
            if (count($messages) < 1) {
                continue;
            }

            $history[$guild] = collect($messages)->take(150)->toArray();
        }

        static::$history = $history;
    }

    /**
     * @param Message $message
     * @return Message
     */
    public static function addHistory(Message $message): Message
    {
        if (!isset(static::$history[$message->guild_id])) {
            static::$history[$message->guild_id] = [];
        }

        static::$history[$message->guild_id][time()] = $message;

        return $message;
    }

    /**
     * @param Guild|string|null $guild
     * @param Channel|null      $channel
     * @return bool
     */
    public static function isRateLimited(Guild|string|null $guild, Channel|null $channel = null): bool
    {
        if ($guild === null) {
            return false;
        }

        if ($guild instanceof Guild) {
            $guild = $guild->id;
        }

        if (Cache::has('rate-limited-' . $guild) == true) {
            return true;
        }

        if (!isset(static::$history[$guild])) {
            return false;
        }

        $count = 0;
        foreach (collect(static::$history[$guild])->reverse() as $time => $message) {
            $time = Carbon::createFromTimestamp($time);
            if ($time->diffInSeconds(now()) < 10) {
                $count++;
                if ($count > 5) {
                    break;
                }
            }
        }

        $isLimited = $count >= 5;

        if ($isLimited) {
            $seconds = 10;
            static::$history[$guild] = [];
            Cache::put('rate-limited-' . $guild, true, Carbon::make("+$seconds seconds"));
            $channel?->sendMessage('Your server is rate limited! Please wait ``' . $seconds . ' seconds``.');
        }

        return $isLimited;
    }

    /**
     * @param Message $message
     * @param Discord $discord
     * @throws Exception
     */
    public function __invoke(Message $message, Discord $discord)
    {
        // do not any think if sender is bot.
        if ($message->user->bot) {
            return;
        }

        // restricts
        $restrict = ChannelRestrict::where('enable', true)->where('server_id', $message->guild_id)->where('channel_id', $message->channel_id)->first();
        if ($restrict !== null && !str_starts_with(mb_strtolower($message->content), Tulpar::getPrefix($message->guild))) {
            foreach (config('tulpar.restricts') as $class) {
                if ((new $class($restrict, $message, $discord))->run()) {
                    return;
                }
            }
        }

        // commands
        $prefix = Tulpar::getPrefix($message->guild_id);
        static::flushCommandHistory();

        // Execute commands.
        if (str_starts_with($message->content, $prefix) && mb_strlen($message->content) > mb_strlen($prefix)) {
            static::addHistory($message);

            if (static::isRateLimited($message->guild_id, $message->channel)) {
                return;
            }

            /** @var CommandInterface $command */
            foreach (config('tulpar.commands', []) as $command) {
                Helpers::call(function () use ($command, $message, $discord) {
                    /** @var BaseCommand $instance */
                    $instance = new $command($message, $discord);
                    $check = $instance->check();
                    static::$commandHistory[$message->id] = (object)['check' => $check, 'command' => $instance];

                    if ($check == CommandValidation::Success || $check == CommandValidation::InvalidArguments) {
                        if ($message->channel->is_private) {
                            if (!$instance::isAllowedPm()) {
                                $message->reply('This command is not allowed in private channel.');
                                return;
                            }
                        }

                        if ($instance->userCommand->hasFlag('help') || $check == CommandValidation::InvalidArguments) {
                            $message->channel->sendMessage($instance->getHelp());
                        }
                        else {
                            Logger::info('Running command: ' . $instance::class);
                            $message->react(Helpers::getRandomEmoticon())->done(function () use ($instance) {
                                new Promise(function () use ($instance) {
                                    Helpers::call(fn () => $instance->run());
                                });
                            });
                        }
                    }
                });
            }

            if (isset(static::$commandHistory[$message->id]) && static::$commandHistory[$message->id]?->check == CommandValidation::NotCommand) {
                $customCommand = CustomCommand::find($message->guild_id, mb_substr($message->content, mb_strlen($prefix)));

                if ($customCommand == null) {
                    if (config('tulpar.command.unknown_alert') == true) {
                        $message->reply('Sorry unknown command requested. :(');
                    }
                }
                else {
                    $customCommand->execute($message, $discord);
                }
            }

            return;
        }

        // Send emoji if message contains bot name.
        if (str_contains(mb_strtolower($message->content), mb_strtolower(config('app.name'))) || str_contains(mb_strtolower($message->content), mb_strtolower($discord->id))) {
            // Log::debug('The command contains bot name. ' . config('app.name'));
            $message->react('💖')->otherwise(function ($exception) {
                Logger::error($exception);
            });
        }

        // Increment xp.
        if (!$message->channel->is_private) {
            $userRank = UserRank::find($message->guild_id, $message->user_id);
            $level = $userRank->getLevelAttribute();
            $userRank->increment('message_count');
            $userRank
                ->incrementGuildMessages($message->guild_id)
                ->incrementChannelMessages($message->channel_id);
            $userRank->save();

            if ($userRank->getLevelAttribute() > $level) {
                $message->channel->sendMessage(Rank::make($message->guild, $message->member, true, false)->setContent('LEVEL UP!')->setReplyTo($message));
            }
        }

        // Execute message filters.
        /** @var FilterInterface $filter */
        foreach (config('tulpar.filters', []) as $filter) {
            if ((new $filter($message, $discord))->run() === null) {
                return;
            }
        }

        // Execute auto responses.
        $autoResponses = AutoResponse::where('guild_id', $message->guild_id)->get();
        foreach ($autoResponses as $autoResponse) {
            if (mb_strtolower($message->content) == mb_strtolower($autoResponse->message)) {
                if (mb_strlen($autoResponse->reply) > 0) {
                    $message->reply($autoResponse->reply);
                }

                if (mb_strlen($autoResponse->emoji) > 0) {
                    $message->react($autoResponse->emoji);
                }

                break;
            }
        }
    }
}
