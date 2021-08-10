<?php


namespace App\Tulpar\Commands\Moderation;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Log;
use Discord\Parts\User\Member;

class BanCommand extends BaseCommand implements CommandInterface
{
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

    public static string $version = '1.1';

    public function run(): void
    {
        /** @var Member $member */
        $member = $this->message->channel->guild->members->get('id', $this->userCommand->getArgument(0));
        $reason = $this->userCommand->hasArgument(1) ? $this->userCommand->getArgument(1) : '';
        $daysToDeleteMessages = $this->userCommand->hasArgument(2) ? $this->userCommand->getArgument(2) : null;

        if (!is_int($daysToDeleteMessages) && !is_null($daysToDeleteMessages)) {
            $this->message->reply(static::getUsages());
            return;
        }

        $member->ban($daysToDeleteMessages, $reason)->done(function () use ($reason, $member) {
            if (mb_strlen($reason)) {
                $this->message->reply('Banned user "' . $member . '" with reason: ``' . $reason . '``');
            }
            else {
                $this->message->reply('Banned user "' . $member . '".');
            }
        }, function ($exception) {
            Log::error($exception);
            $this->message->reply('An error occurred when banning the user.');
        });
    }
}
