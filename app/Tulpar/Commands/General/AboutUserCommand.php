<?php


namespace App\Tulpar\Commands\General;


use App\Enums\CommandCategory;
use App\Models\UserRank;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\CommandTraits\HasMemberArgument;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Guild\Role;
use Illuminate\Support\Carbon;

class AboutUserCommand extends BaseCommand implements CommandInterface
{
    use HasMemberArgument;

    public static string $command = 'about-user';

    public static string $description = 'Show about user.';

    public static array $permissions = [];

    public static array $usages = [
        'user-id',
        '@username',
    ];

    public static string $category = CommandCategory::General;

    public function run(): void
    {
        $member = $this->getMemberArgument(failMessage: true);
        if ($member === null) {
            return;
        }

        $registered_at = $member->user->createdTimestamp();
        $joined_at = $member->joined_at;
        $roles = [];

        /** @var Role $role */
        foreach ($member->roles as $role) {
            $roles[$role->id] = '<@&' . $role->id . '>';
        }

        $embed = new Embed($this->discord);
        $embed->setAuthor($member->username, $member->user->getAvatarAttribute());
        $embed->setThumbnail($member->user->getAvatarAttribute());
        $embed->setDescription(Helpers::userTag($member->id));

        $embed->addFieldValues('Joined At', $joined_at->shortRelativeToNowDiffForHumans(), true);
        $embed->addFieldValues('Registered At', Carbon::createFromTimestamp($registered_at)->shortRelativeToNowDiffForHumans(), true);
        $embed->addFieldValues('Rank', 0, true);

        $embed->addFieldValues('Messages in ````' . $this->message->guild->name . '````', ($rank = UserRank::find($this->message->guild_id, $this->message->user_id))->message_count, true);
        $embed->addFieldValues('Messages in ````#' . $this->message->channel->name . '````', $rank->getChannelMessageCount($this->message->channel_id), true);

        $embed->addFieldValues('Roles [' . count($roles) . ']', implode(' ', $roles), false);

        $this->message->channel->sendEmbed($embed);
    }
}
