<?php


namespace App\Tulpar\Commands\Chat;


use App\Enums\CommandCategory;
use App\Support\Str;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Parts\Channel\Channel;

class TemporaryChannelCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'temp-channel';

    public static string $description = 'Create temporary voice channel.';

    public static array $usages = [
        '',
    ];

    public static array $permissions = ['root'];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Chat;

    public static array $channels = [];

    public function run(): void
    {
        $channel = new Channel($this->discord);
        $channel->guild = $this->message->guild;
        $channel->guild_id = $this->message->guild_id;
        $channel->name = 'T#' . Str::random(6);
        $channel->type = 2;

        $this->message->guild->channels->save($channel)->then(function () use ($channel) {
            if (Helpers::getMemberVoiceChannel($this->message->member->id) != null) {
                $this->message->member->moveMember($channel);
            }

            static::$channels[$channel->id] = (object)[
                'created_at' => now(),
                'owner' => $this->message->member->id,
                'owner_joined' => false,
                'members' => [],
                'channel' => $channel,
            ];
        });
    }
}
