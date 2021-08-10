<?php


namespace App\Tulpar\Events\Message;


use App\Enums\CommandValidation;
use App\Models\AutoResponse;
use App\Models\CustomCommand;
use App\Models\UserRank;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Contracts\FilterInterface;
use App\Tulpar\Helpers;
use App\Tulpar\Log;
use App\Tulpar\Tulpar;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use stdClass;

class CreateEvent
{
    /**
     * @var array $commandHistory
     */
    public static array $commandHistory = [];

    public function __invoke(Message $message, Discord $discord)
    {
        // do not any think if sender is bot.
        if ($message->user->bot) {
            return;
        }

        if (str_starts_with($message->content, Tulpar::getPrefix())) {
            static::$commandHistory[$message->id] = new stdClass;
            static::$commandHistory[$message->id]->check = CommandValidation::NotCommand;
            static::$commandHistory[$message->id]->content = $message->content;

            /** @var CommandInterface $command */
            foreach (config('tulpar.commands', []) as $command) {
                Helpers::call(function () use ($command, $message, $discord) {
                    /** @var BaseCommand $instance */
                    $instance = new $command($message, $discord);
                    $check = $instance->check();

                    if ($check == CommandValidation::Success || $check == CommandValidation::InvalidArguments) {
                        static::$commandHistory[$message->id]->check = $check;

                        if ($message->channel->is_private) {
                            if (!$instance::isAllowedPm()) {
                                Log::info(sprintf(
                                    'Requested not allowed in pm command from %s, "%s"',
                                    '<@' . $message->user_id . '>',
                                    $message->content,
                                ));
                                $message->channel->sendMessage('This command is not allowed in private channel.');
                                return;
                            }
                        }

                        if ($instance->userCommand->hasFlag('help') || $check == CommandValidation::InvalidArguments) {
                            $message->channel->sendMessage($instance->getHelp());
                            return;
                        }
                        else {
                            Log::info('Running command: ' . $instance::class);
                            $message->react(Tulpar::getRandomEmoticon())->done(function () use ($instance) {
                                Helpers::call(fn () => $instance->run());
                            });
                            return;
                        }
                    }
                });
            }

            if (static::$commandHistory[$message->id]?->check == CommandValidation::NotCommand) {
                $customCommand = CustomCommand::find($message->guild_id, mb_substr($message->content, mb_strlen(Tulpar::getPrefix())));

                if ($customCommand == null) {
                    Log::notice('Unknown command requested: "' . $message->content . '"');
                    $message->channel->sendMessage('Sorry unknown command requested. :(');
                }
                else {
                    $customCommand->execute($message, $discord);
                }
            }
        }
        else {
            /** @var FilterInterface $filter */
            foreach (Tulpar::$filters as $filter) {
                $instance = new $filter($message, $discord);
                $instance->run();
            }

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

        // Send emoji if message contains bot name.
        if (str_contains(mb_strtolower($message->content), mb_strtolower(config('app.name'))) || str_contains(mb_strtolower($message->content), mb_strtolower($discord->id))) {
            Log::debug('The command contains bot name. ' . config('app.name'));
            $message->react('💖')->otherwise(function ($exception) {
                Log::error($exception);
            });
        }

        // Increment xp.
        if (!$message->channel->is_private) {
            $userRank = UserRank::find($message->guild_id, $message->user_id);
            $userRank->increment('message_count');
            $userRank->save();
        }
    }
}
