<?php

namespace App\Tulpar\Commands\Rank;

use App\Enums\CommandCategory;
use App\Models\Rank;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use Discord\Parts\User\Member;

class RankCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'rank';

    public static string $description = 'Show the rank';

    public static array $permissions = ['root'];

    public static array $usages = [
        '',
        '@username',
    ];

    public static string $category = CommandCategory::Rank;

    public function run(): void
    {
        $member = $this->userCommand->hasArgument(0) ? $this->userCommand->getArgument(0) : $this->message->member->id;
        $member = $this->message->guild->members->get('id', $member);
        if (!$member instanceof Member) {
            $this->message->reply('Invalid member');
            return;
        }

        $this->message->channel->sendMessage(Rank::make($this->message->guild, $member, true, true));
    }
}
