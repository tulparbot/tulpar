<?php


namespace App\Tulpar\Commands\Moderation;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use App\Tulpar\Tulpar;
use Discord\Parts\User\Member;

class BanCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'ban';

    public static string $description = 'Ban the user.';

    public static array $permissions = ['administrator'];

    public static array $usages = [
        'user-id',
        '@username',
        '@username "[reason]"',
        '@username "[reason]" [days-to-delete-messages]',
    ];

    public static array $requires = [0];

    public function run(): void
    {
        $user_id = $this->userCommand->getArgument(0);
        $user = Helpers::userTag($user_id);
        $reason = $this->userCommand->hasArgument(1) ? $this->userCommand->getArgument(1) : '';
        $daysToDeleteMessages = $this->userCommand->hasArgument(2) ? $this->userCommand->getArgument(2) : null;

        if (!is_int($daysToDeleteMessages) && !is_null($daysToDeleteMessages)) {
            $this->message->channel->sendMessage(static::getUsages());
            return;
        }

        if (mb_strlen($reason)) {
            $this->message->channel->sendMessage('Banned user "' . $user . '" with reason: ``' . $reason . '``');
        } else {
            $this->message->channel->sendMessage('Banned user "' . $user . '".');
        }

        Tulpar::findGuildFrom($this->message)->members->fetch($user_id)->then(function (Member $member) use ($reason, $daysToDeleteMessages) {
            $member->ban($daysToDeleteMessages, $reason);
        });
    }
}
