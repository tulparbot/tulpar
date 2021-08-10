<?php


namespace App\Tulpar\Commands\Moderation;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Parts\Guild\Ban;

class UnbanCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'unban';

    public static string $description = 'Unban the banned user.';

    public static array $permissions = ['administrator'];

    public static array $usages = [
        'user-id',
        '@username',
    ];

    public static array $requires = [0];

    public function run(): void
    {
        $user_id = $this->userCommand->getArgument(0);
        $user = Helpers::userTag($user_id);
        $channel = $this->message->channel;

        Helpers::findGuildFrom($this->message)->unban($user_id)->then(function (Ban $ban) use ($channel, $user) {
            $channel->sendMessage('Unbanned: ' . $user);
        });
    }
}
