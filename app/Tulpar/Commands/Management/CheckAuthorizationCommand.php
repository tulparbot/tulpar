<?php


namespace App\Tulpar\Commands\Management;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;

class CheckAuthorizationCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'whoami';

    public static string $description = 'Get the role in bot.';

    public static array $permissions = [];

    public static bool $allowPm = true;

    public function run(): void
    {
        $user = $this->message->user;
        $guild = Helpers::findGuild($this->message->guild_id, $this->discord);
        $channel = $this->message->channel;

        if (Helpers::isRoot($user)) {
            $channel->sendMessage('Your role is: Root!');
        }
        else {
            Helpers::whenAdmin($user, $guild, function () use ($channel) {
                $channel->sendMessage('Your role is: Administrator');
            }, function () use ($channel) {
                $channel->sendMessage('Your role is: Member');
            });
        }
    }
}
