<?php


namespace App\Tulpar\Commands\Basic;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Guild\Role;

class AboutServerCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'about-server';

    public static string $description = 'Show about the server.';

    public static array $permissions = [];

    public static string $category = CommandCategory::General;

    public function run(): void
    {
        $guild = $this->message->channel->guild;

        $owner = $guild->owner;
        $region = $guild->region;
        $total_users = $guild->member_count;
        $max_total_users = $guild->max_members;
        $roles = $guild->roles;
        $_roles = [];
        $emojis = $guild->emojis;
        $channels = $guild->channels;

        $channelCount = function (int $type) use ($channels) {
            $count = 0;
            /** @var Channel $channel */
            foreach ($channels as $channel) {
                if ($channel->type == $type) {
                    $count++;
                }
            }

            return $count;
        };

        /** @var Role $role */
        foreach ($roles as $role) {
            $_roles[$role->id] = '<@&' . $role->id . '>';
        }

        $embed = new Embed($this->discord);
        $embed->setAuthor($this->message->user->username, $this->message->user->avatar);

        $embed->addFieldValues('Owner', Helpers::userTag($owner->id), true);
        $embed->addFieldValues('Region', $region, true);
        $embed->addFieldValues('Emojis', $emojis->count(), true);

        $embed->addFieldValues('Users', $total_users, true);
        $embed->addFieldValues('Max Users', $max_total_users, true);
        $embed->addFieldValues('Total Channels', $channels->count(), true);

        $embed->addFieldValues('Text Channels', $channelCount(0), true);
        $embed->addFieldValues('Voice Channels', $channelCount(2), true);
        $embed->addFieldValues('Category Channels', $channelCount(4), true);
        $embed->addFieldValues('News Channels', $channelCount(5), true);
        $embed->addFieldValues('Public Channels', $channelCount(11), true);
        $embed->addFieldValues('Private Channels', $channelCount(12), true);

        $embed->addFieldValues('Roles', implode(' ', $_roles));

        $this->message->channel->sendEmbed($embed);
    }
}
