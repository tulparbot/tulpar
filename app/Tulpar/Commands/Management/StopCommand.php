<?php


namespace App\Tulpar\Commands\Management;


use App\Commands\StartCommand;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;

class StopCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'stop';

    public static string $description = 'Stop the bot.';

    public static array $usages = [''];

    public static array $permissions = ['root'];

    public static bool $allowPm = true;

    public function run(): void
    {
        $discord = $this->discord;
        $this->message->channel->sendMessage(sprintf(
            '%s is stopping...',
            config('app.name'),
        ))->then(function () use ($discord) {
            StartCommand::$restartReceived = false;
            sleep(1);

            $discord->close(false);
            sleep(1);

            exit;
        });
    }
}
