<?php

namespace App\Tulpar\Commands\Rank;

use App\Enums\CommandCategory;
use App\Models\Rank;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\CommandTraits\HasMemberArgument;
use App\Tulpar\Contracts\CommandInterface;

class RankCommand extends BaseCommand implements CommandInterface
{
    use HasMemberArgument;

    public static string $command = 'rank';

    public static string $description = 'Show the rank';

    public static array $permissions = ['root'];

    public static string $version = 'v1.1';

    public static array $usages = [
        '',
        '@username',
    ];

    public static string $category = CommandCategory::Rank;

    public function run(): void
    {
        $member = $this->getMemberArgument(0, true);
        if ($member === false) {
            return;
        }
        else if ($member === null) {
            $member = $this->message->member;
        }

        $this->message->channel->sendMessage(Rank::make($this->message->guild, $member, true, true));
    }
}
