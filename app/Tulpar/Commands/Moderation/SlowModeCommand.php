<?php


namespace App\Tulpar\Commands\Moderation;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Builders\Components\Option;
use Discord\Builders\Components\SelectMenu;
use Discord\Builders\MessageBuilder;
use Discord\Helpers\Collection;
use Discord\Parts\Interactions\Interaction;

class SlowModeCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'slowmode';

    public static string $description = 'Enable or disable slow mode in a channel.';

    public static array $permissions = ['manage_channels'];

    public static array $usages = [
        'off',
        '10s',
        '10m',
        '1h',
    ];

    public static array $requires = [];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Moderation;

    public static array $slowmodes = [
        '5 seconds' => 5,
        '10 seconds' => 10,
        '15 seconds' => 15,
        '30 seconds' => 30,
        '1 minute' => 60,
        '2 minutes' => 60 * 2,
        '5 minutes' => 60 * 5,
        '10 minutes' => 60 * 10,
        '15 minutes' => 60 * 15,
        '30 minutes' => 60 * 30,
        '1 hours' => 60 * 60,
        '2 hours' => (60 * 60) * 2,
        '6 hours' => (60 * 60) * 6,
    ];

    public function run(): void
    {
        $menu = SelectMenu::new()->addOption(Option::new($this->translate('Off'), 0));
        foreach (static::$slowmodes as $key => $value) {
            $menu = $menu->addOption(Option::new($key, $value));
        }

        $menu->setListener(function (Interaction $interaction, Collection $collection) {
            if ($interaction->user->id != $this->message->user_id) {
                return;
            }

            /** @var Option $option */
            $option = $collection->first();
            $value = (int)$option->getValue();

            $key = 'off';
            foreach (static::$slowmodes as $k => $v) {
                if ($v == $value) {
                    $key = $k;
                }
            }

            Helpers::setRateLimitPerUser($this->message->channel, $value);
            $this->message->delete()->then(function () use ($interaction, $key) {
                $message = $this->translate(':channel is no longer slow modded.', ['channel' => '<#' . $this->message->channel_id . '>']);
                if ($key != 'off') {
                    $message = $this->translate(':channel is now in slow motion. Regular users can only post once every :seconds', [
                        'channel' => '<#' . $this->message->channel_id . '>',
                        'seconds' => $key,
                    ]);
                }

                $interaction->message->delete();
                $interaction->channel->sendMessage($message);
            });
        }, $this->discord);

        $this->message->channel->sendMessage(MessageBuilder::new()
            ->setReplyTo($this->message)
            ->setContent($this->translate('Please select a time or disable slow mode.'))
            ->addComponent($menu));

        return;

        $argument = $this->userCommand->getArgument(0);
        if ($argument == 'off') {
            Helpers::setRateLimitPerUser($this->message->channel, 0);
            return;
        }

        if (!array_key_exists($argument, static::$slowmodes)) {
            $this->message->reply('Invalid time requested. You only use: ' . implode(', ', array_keys(static::$slowmodes)));
            return;
        }

    }
}
