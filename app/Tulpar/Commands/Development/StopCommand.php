<?php


namespace App\Tulpar\Commands\Development;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Dialog;
use App\Tulpar\Tulpar;
use Discord\Parts\Interactions\Interaction;

class StopCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'stop';

    public static string $description = 'Stop the bot.';

    public static array $usages = [''];

    public static array $permissions = ['root'];

    public static bool $allowPm = true;

    public static string $version = '1.1';

    public static string $category = CommandCategory::Management;

    public function run(): void
    {
        $this->message->channel->sendMessage((Dialog::confirm('Are you sure to stop ' . config('app.name') . '?', listenerNo: function (Interaction $interaction) {
            $interaction->message->delete();
        }, listenerYes: function (Interaction $interaction) {
            $this->message->reply(config('app.name') . ' is stopping...')->done(function () {
                Tulpar::getInstance()->stop();
            });
        }))->setReplyTo($this->message));
    }
}
