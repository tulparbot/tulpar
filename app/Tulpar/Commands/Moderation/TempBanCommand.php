<?php


namespace App\Tulpar\Commands\Moderation;


use App\Enums\CommandCategory;
use App\Models\TempBan;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Logger;
use Discord\Parts\User\Member;
use Exception;
use Illuminate\Support\Carbon;

class TempBanCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'temp-ban';

    public static string $description = 'Temporary ban the user.';

    public static array $permissions = ['ban_members'];

    public static array $usages = [
        '@username "1 hour"',
        '@username "1 hour 30 seconds" "[reason]"',
    ];

    public static array $requires = [0];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Moderation;

    public function run(): void
    {
        /** @var Member $member */
        $member = $this->message->channel->guild->members->get('id', $this->userCommand->getArgument(0));
        if (!$member instanceof Member) {
            $this->message->reply($this->translate('You can only ban members!'));
            return;
        }

        try {
            $end = Carbon::make($this->userCommand->getArgument(0));
            if ($end === null) {
                $this->message->reply($this->translate('Invalid time requested!'));
                return;
            }
        } catch (Exception $exception) {
            $this->message->reply($this->translate('Invalid time requested!'));
            return;
        }

        $reason = $this->userCommand->hasArgument(1) ? $this->userCommand->getArgument(1) : '';
        $member->ban(null, $reason)->done(function () use ($end, $reason, $member) {
            if (mb_strlen($reason)) {
                $this->message->reply($this->translate('Temporary banned user ":member" with reason: ``:reason``', [
                    'member' => $member,
                    'reason' => $reason,
                ]));
            }
            else {
                $this->message->reply($this->translate('Temporary banned user ":member"', ['member' => $member]));
            }

            TempBan::create([
                'server_id' => $member->guild_id,
                'member_id' => $member->id,
                'reason' => $reason,
                'end_at' => $end,
            ]);
        }, function ($exception) {
            Logger::error($exception);
            $this->message->reply($this->translate('An error occurred when banning the user.'));
        });
    }
}
