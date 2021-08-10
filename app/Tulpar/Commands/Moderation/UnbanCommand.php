<?php


namespace App\Tulpar\Commands\Moderation;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use Discord\Parts\Guild\Ban;
use Discord\Parts\User\Member;

class UnbanCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'unban';

    public static string $description = 'Unban the banned user.';

    public static array $permissions = ['ban_members'];

    public static array $usages = [
        'user-id',
        '@username',
    ];

    public static array $requires = [0];

    public static string $version = '1.1';

    public function run(): void
    {
        /** @var Member $member */
        $member = $this->message->channel->guild->members->get('id', $this->userCommand->getArgument(0));
        $this->message->channel->guild->unban($member)->done(function (Ban $ban) use ($member) {
            $this->message->reply('Member is unbanned: ' . $member);
        });
    }
}
