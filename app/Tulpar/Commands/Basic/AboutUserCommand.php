<?php


namespace App\Tulpar\Commands\Basic;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Guild\Role;
use Discord\Parts\User\Member;
use Illuminate\Support\Carbon;

class AboutUserCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'about-user';

    public static string $description = 'Show about user.';

    public static array $permissions = [];

    public static array $usages = [
        'user-id',
        '@username',
    ];

    public function run(): void
    {
        if ($this->userCommand->hasArgument(0)) {
            /** @var Member $member */
            $member = $this->message->channel->guild->members->get('id', $this->userCommand->getArgument(0));
        }
        else {
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

        $embed->addFieldValues('Joined At', $joined_at->shortRelativeToNowDiffForHumans(), true);
        $embed->addFieldValues('Registered At', Carbon::createFromTimestamp($registered_at)->shortRelativeToNowDiffForHumans(), true);
        $embed->addFieldValues('Roles [' . count($roles) . ']', implode(' ', $roles), false);

        $this->message->channel->sendEmbed($embed);
    }
}
