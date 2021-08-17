<?php

namespace App\Tulpar\Events\General;

use App\Tulpar\Commands\Chat\TemporaryChannelCommand;
use App\Tulpar\Tulpar;
use Discord\Discord;
use Discord\Parts\Channel\Channel;
use Discord\Parts\User\Member;
use Discord\Parts\WebSockets\VoiceStateUpdate;

class VoiceStateUpdateEvent
{
    public function removeMember(string $member_id): void
    {
        $_ = [];
        $channels = Tulpar::$voiceChannels;
        foreach ($channels as $channelId => $channel) {
            $_[$channelId] = [];

            /** @var Member $member */
            foreach ($channel as $memberId => $member) {
                if ($memberId != $member_id) {
                    $_[$channelId][$memberId] = $member;
                }
            }
        }

        Tulpar::$voiceChannels = $_;
    }

    public function __invoke(VoiceStateUpdate $voiceStateUpdate, Discord $discord)
    {
        $this->removeMember($voiceStateUpdate->member->id);

        if ($voiceStateUpdate->channel != null) {
            if (!array_key_exists($voiceStateUpdate->channel_id, Tulpar::$voiceChannels)) {
                Tulpar::$voiceChannels[$voiceStateUpdate->channel_id] = [];
            }

            Tulpar::$voiceChannels[$voiceStateUpdate->channel_id][$voiceStateUpdate->member->id] = $voiceStateUpdate->member;
        }

        foreach (TemporaryChannelCommand::$channels as $channel_id => $obj) {
            if ($obj->owner_joined == false) {
                if ($voiceStateUpdate->channel_id == $channel_id) {
                    if ($voiceStateUpdate->member->id == $obj->owner) {
                        TemporaryChannelCommand::$channels[$channel_id]->owner_joined = true;
                    }

                    TemporaryChannelCommand::$channels[$channel_id]->members[$voiceStateUpdate->member->id] = $voiceStateUpdate->member;
                }
            }

            if ($voiceStateUpdate->channel_id == null) {
                if ($voiceStateUpdate->member->id == $obj->owner) {
                    /** @var Channel $channel */
                    $channel = $obj->channel;
                    $id = $channel->id;

                    $channel->guild->channels->delete($channel)->done(function () use ($id) {
                        unset(TemporaryChannelCommand::$channels[$id]);
                    });
                }
                else {
                    unset(TemporaryChannelCommand::$channels[$channel_id]->members[$voiceStateUpdate->member->id]);
                }
            }
        }
    }
}
