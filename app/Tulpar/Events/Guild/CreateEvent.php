<?php

namespace App\Tulpar\Events\Guild;

use App\Models\Server;
use Discord\Discord;
use Discord\Parts\Guild\Guild;

class CreateEvent
{
    public function __invoke(Guild $guild, Discord $discord)
    {
        $server = Server::where('server_id', $guild->id)->first();
        if ($server == null) {
            Server::create([
                'server_id' => $guild->id,
                'name' => $guild->name,
                'icon' => $guild->icon,
                'description' => $guild->description,
                'region' => $guild->region,
                'preferred_locale' => $guild->preferred_locale,
                'features' => serialize($guild->features),
                'large' => $guild->large,
                'verification_level' => $guild->verification_level,
                'premium_tier' => $guild->premium_tier,
                'premium_subscription_count' => $guild->premium_subscription_count,
                'member_count' => $guild->member_count,
                'max_members' => $guild->max_members,
                'max_video_channel_users' => $guild->max_video_channel_users,
                'owner_id' => $guild->owner_id,
                'application_id' => $guild->application_id,
                'system_channel_id' => $guild->system_channel_id,
                'rules_channel_id' => $guild->rules_channel_id,
                'public_updates_channel_id' => $guild->public_updates_channel_id,
                'roles' => $guild->roles->serialize(),
                'channels' => $guild->channels->serialize(),
                'members' => $guild->members->serialize(),
                'invites' => $guild->invites->serialize(),
                'bans' => $guild->bans->serialize(),
                'emojis' => $guild->emojis->serialize(),
                'joined_at' => $guild->joined_at,
            ]);
        }
    }
}
