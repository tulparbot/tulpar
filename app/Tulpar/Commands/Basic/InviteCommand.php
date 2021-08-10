<?php


namespace App\Tulpar\Commands\Basic;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;

class InviteCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'invite';

    public static string $description = 'Get invite link';

    public static array $permissions = [];

    public static bool $allowPm = true;

    public function run(): void
    {
        $this->message->channel->sendMessage('There is my invite link: ' . $this->discord->application->invite_url);
    }
}
