<?php

namespace App\Tulpar\Events\General;

use App\Tulpar\Tulpar;
use Discord\Discord;
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
    }
}
