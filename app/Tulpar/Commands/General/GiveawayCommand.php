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
use Illuminate\Support\Carbon;
use React\EventLoop\Timer\Timer;

class GiveawayCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'giveaway';

    public static string $description = 'Make a giveaway.';

    public static array $permissions = [];

    public static string $category = CommandCategory::General;

    public static array $usages = [
        '"giveaway title"',
        '"giveaway title" "+3 days"',
    ];

    public static array $requires = [0];

    public static array $votes = [];

    public function run(): void
    {
        $title = $this->userCommand->getArgument(0);
        $duration = Carbon::make($this->userCommand->getArgument(1) ?? '+10 minutes');
        $emoji = '🎉';

        $embed = new Embed($this->discord);
        $embed->setTitle($title);
        $embed->setDescription('React with ' . $emoji . ' to enter!' . PHP_EOL . 'Duration: ' . $duration->addSeconds()->diffForHumans());

        $this->message->channel->sendEmbed($embed)->done(function (Message $message) use ($duration, $emoji) {
            static::$votes[$message->id] = [];
            $message->react($emoji);

            Tulpar::getInstance()->getDiscord()->getLoop()->addTimer($duration->diffInSeconds() + 1, function (Timer $timer) use ($message, $duration) {
                Tulpar::getInstance()->getDiscord()->getLoop()->cancelTimer($timer);
                $winners = [];

                if (array_key_exists($message->id, static::$votes) && count(static::$votes) > 0) {
                    $winners[] = static::$votes[$message->id][array_rand(static::$votes[$message->id])];
                }

                $winners = collect($winners)->unique()->filter(function ($value) {
                    return !$this->message->guild->members->get('id', $value)->user->bot;
                });

                if ($winners->count() > 0) {
                    $builder = new MessageBuilder();
                    $builder->setContent('Winners:');
                    $builder->setReplyTo($message);
                    $embed = new Embed($this->discord);
                    $embed->setAuthor('Winner!');
                    $embed->setDescription(Helpers::userTag($winners[0]));
                    $builder->addEmbed($embed);
                    $message->channel->sendMessage($builder);
                    return;
                }

                $message->reply('Nobody won the lottery.');
            });
        });
    }
}
