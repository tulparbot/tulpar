<?php


namespace App\Tulpar\Commands\General;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use App\Tulpar\Tulpar;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Exception;
use Illuminate\Support\Carbon;
use React\EventLoop\Timer\Timer;

class GiveawayCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'giveaway';

    public static string $description = 'Make a giveaway.';

    public static array $permissions = ['administrator'];

    public static string $category = CommandCategory::General;

    public static array $usages = [
        '"giveaway title"',
        '"giveaway title" "+3 days"',
        'message-id --re',
    ];

    public static array $requires = [0];

    public static array $votes = [];

    public function giveaway(Message $message)
    {
        $winners = [];

        if (isset(static::$votes[$message->id]) && count(static::$votes) > 0 && count(static::$votes[$message->id]) > 0) {
            $winners[] = static::$votes[$message->id][array_rand(static::$votes[$message->id])];
        }

        $winners = collect($winners)->unique()->filter(function ($value) {
            return !$this->message->guild->members->get('id', $value)->user->bot;
        });

        if ($winners->count() > 0) {
            $builder = new MessageBuilder();
            $builder->setContent($this->translate('Winners:'));
            $builder->setReplyTo($message);
            $embed = new Embed($this->discord);
            $embed->setAuthor($this->translate('Winner!'));
            $embed->setDescription(Helpers::userTag($winners[0]));
            $builder->addEmbed($embed);
            $message->channel->sendMessage($builder);
            return;
        }

        $message->reply($this->translate('Nobody won the lottery.'));
    }

    public function run(): void
    {
        if ($this->userCommand->hasFlag('re')) {
            if (!$this->userCommand->hasArgument(0)) {
                $this->message->reply(static::getHelp());
                return;
            }

            $this->message->channel->messages->fetch($this->userCommand->getArgument(0))->done(function (Message $message) {
                $this->giveaway($message);
            });

            return;
        }

        $title = $this->userCommand->getArgument(0);
        try {
            $duration = Carbon::make($this->userCommand->getArgument(1) ?? '+10 minutes');
        } catch (Exception $exception) {
            $this->message->reply($this->translate('Invalid duration'));
            return;
        }
        $emoji = '🎉';

        if (mb_strlen($title) < 1 || mb_strlen($title) > 200) {
            $this->message->reply($this->translate('The title is must can not be longer than 200 characters'));
            return;
        }

        if ($duration == null) {
            $this->message->reply($this->translate('Invalid duration'));
            return;
        }

        $embed = new Embed($this->discord);
        $embed->setTitle($title);
        $embed->setDescription($this->translate('React with :emoji to enter!:eolDuration: :duration', [
            'emoji' => $emoji,
            'duration' => $duration->addSeconds()->diffForHumans(),
        ]));

        $this->message->channel->sendEmbed($embed)->done(function (Message $message) use ($duration, $emoji) {
            static::$votes[$message->id] = [];
            $message->react($emoji);

            Tulpar::getInstance()->getDiscord()->getLoop()->addTimer($duration->diffInSeconds() + 1, function (Timer $timer) use ($message, $duration) {
                Tulpar::getInstance()->getDiscord()->getLoop()->cancelTimer($timer);
                $this->giveaway($message);
            });
        });
    }
}
