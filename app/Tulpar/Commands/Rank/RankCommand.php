<?php

namespace App\Tulpar\Commands\Rank;

use App\Enums\Align;
use App\Models\UserRank;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Parts\Embed\Embed;
use Discord\Parts\User\Member;

class RankCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'rank';

    public static string $description = 'Show the rank';

    public static array $permissions = [];

    public static array $usages = [
        '',
        '@username',
    ];

    public function run(): void
    {
        $user_id = $this->userCommand->hasArgument(0) ? $this->userCommand->getArgument(0) : $this->message->user_id;
        $this->message->channel->guild->members->fetch($user_id)->done(function (Member $member) use ($user_id) {
            $userRank = UserRank::find($this->message->guild_id, $user_id);
            $messages = $userRank->message_count;
            $xp = $userRank->xp;
            $level = $userRank->level;

            $embed = new Embed($this->discord);
            $embed->title = $member->nick ?? $member->username;
            $embed->setThumbnail($member->user->avatar);
            $embed->addFieldValues('Level', '``' . Helpers::line($level, Align::Center, 7) . '``', true);
            $embed->addFieldValues('XP', '``' . Helpers::line($xp, Align::Center, 7) . '``', true);
            $embed->addFieldValues('Total Messages', '``' . Helpers::line($messages, Align::Center, 7) . '``', true);
            $embed->addFieldValues('Since', $userRank->created_at);
            $embed->addFieldValues('Records', 'No any bans, warnings, mutes or kicks found.');

            $this->message->channel->sendEmbed($embed);
        });
//        Tulpar::findGuild($this->message->guild_id, $this->discord)->members->freshen()->done(function (MemberRepository $repository) use ($user_id) {
//            $repository->fetch($user_id)->done(function ($member) use ($user_id) {
//                $userRank = UserRank::find($this->message->guild_id, $user_id);
//                $messages = $userRank->message_count;
//                $rank = $userRank->xp;
//                $level = $userRank->level;
//
//                $embed = new Embed($this->discord);
//                $embed->title = $member->name;
//                $embed->description = '``Level #' . $level . '``';
//
//                $this->message->channel->sendEmbed($embed);
//                $this->message->channel->sendMessage('Your xp: ' . $rank . ' level: ' . $level . ' total messages: ' . $messages);
//            });
//        });
    }
}
