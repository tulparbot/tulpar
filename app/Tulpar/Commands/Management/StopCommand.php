<?php


namespace App\Tulpar\Commands\Management;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Tulpar;

class StopCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'stop';

    public static string $description = 'Stop the bot.';

    public static array $usages = [''];

    public static array $permissions = ['root'];

    public static bool $allowPm = true;

    public static string $version = '1.1';

    public function run(): void
    {
        $this->message->reply(config('app.name') . ' is stopping...')->done(function () {
            Tulpar::getInstance()->stop();
        });
    }
}
