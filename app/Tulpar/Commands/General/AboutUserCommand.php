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
        if ($member === false) {
            return;
        }

        if ($member === null) {
            $member = $this->message->member;
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

        $embed->addFieldValues($this->translate('Joined At'), $joined_at->shortRelativeToNowDiffForHumans(), true);
        $embed->addFieldValues($this->translate('Registered At'), Carbon::createFromTimestamp($registered_at)->shortRelativeToNowDiffForHumans(), true);
        $embed->addFieldValues($this->translate('Rank'), 0, true);

        $embed->addFieldValues($this->translate('Messages in ````:server````', ['server' => $this->message->guild->name]), ($rank = UserRank::find($this->message->guild_id, $this->message->user_id))->message_count, true);
        $embed->addFieldValues($this->translate('Messages in ````:channel````', ['channel' => '#' . $this->message->channel->name]), $rank->getChannelMessageCount($this->message->channel_id), true);

        $embed->addFieldValues($this->translate('Roles [:count]', ['count' => count($roles)]), implode(' ', $roles), false);

        $this->message->channel->sendEmbed($embed);
    }
}
