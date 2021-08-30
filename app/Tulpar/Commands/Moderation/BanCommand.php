<?php


namespace App\Tulpar\Commands\Moderation;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\CommandTraits\HasMemberArgument;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Logger;
use Discord\Parts\User\Member;

class BanCommand extends BaseCommand implements CommandInterface
{
    use HasMemberArgument;

    public static string $command = 'ban';

    public static string $description = 'Ban the user.';

    public static array $permissions = ['ban_members'];

    public static array $usages = [
        'user-id',
        '@username',
        '@username "[reason]"',
        '@username "[reason]" [days-to-delete-messages]',
    ];

    public static array $requires = [0];

    public static string $version = '1.2';

    public static string $category = CommandCategory::Moderation;

    public function run(): void
    {
        $member = $this->getMemberArgument(0, true);
        if (!$member instanceof Member) {
            return;
        }

        $reason = $this->userCommand->hasArgument(1) ? $this->userCommand->getArgument(1) : '';
        $daysToDeleteMessages = $this->userCommand->hasArgument(2) ? $this->userCommand->getArgument(2) : null;

        if (!is_int($daysToDeleteMessages) && !is_null($daysToDeleteMessages)) {
            $this->message->reply(static::getUsages());
            return;
        }

        $member->ban($daysToDeleteMessages, $reason)->done(function () use ($reason, $member) {
            if (mb_strlen($reason)) {
                $this->message->reply($this->translate('Banned user ":member" with reason: ``:reason``', [
                    'member' => $member,
                    'reason' => $reason,
                ]));
            }
            else {
                $this->message->reply($this->translate('Banned user: ":member"', [
                    'member' => $member,
                ]));
            }
        }, function ($exception) {
            Logger::error($exception);
            $this->message->reply($this->translate('An error occurred when banning the user.'));
        });
    }
}
