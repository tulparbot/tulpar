<?php


namespace App\Tulpar\Commands\Moderation;


use App\Enums\CommandCategory;
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

    public static string $category = CommandCategory::Moderation;

    public function run(): void
    {
        /** @var Member $member */
        $member = $this->message->channel->guild->members->get('id', $this->userCommand->getArgument(0));
        if (!$member instanceof Member) {
            $this->message->reply($this->translate('You can only unban members!'));
            return;
        }

        $this->message->channel->guild->unban($member)->done(function (Ban $ban) use ($member) {
            $this->message->reply($this->translate('Member is unbanned: :member', ['member' => $member]));
        });
    }
}
