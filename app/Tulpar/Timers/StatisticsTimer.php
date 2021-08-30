<?php

namespace App\Tulpar\Timers;

use App\Models\ServerStatisticsChannel;
use App\Tulpar\Tulpar;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Member;
use Discord\Repository\Guild\MemberRepository;
use React\EventLoop\TimerInterface;

class StatisticsTimer
{
    public static function run(TimerInterface $timer)
    {
        foreach (ServerStatisticsChannel::all() as $statisticsChannel) {
            /** @var Guild|null $guild */
            $guild = Tulpar::getInstance()->getDiscord()->guilds->get('id', $statisticsChannel->guild_id);
            if ($guild == null) {
                continue;
            }

            $guild->channels->fetch($statisticsChannel->channel_id)->done(function (Channel $channel) use ($statisticsChannel) {
                switch ($statisticsChannel->type) {
                    case 'total_users':
                        $channel->name = _text($channel->guild, ':count Total Members', ['count' => $channel->guild->member_count]);
                        $channel->guild->channels->save($channel);
                        break;

                    case 'bot_users':
                        $channel->guild->members->freshen()->done(function (MemberRepository $memberRepository) use ($channel) {
                            $count = 0;

                            /** @var Member $member */
                            foreach ($memberRepository as $member) {
                                if ($member->user->bot) {
                                    $count++;
                                }
                            }

                            $channel->name = _text($channel->guild, ':count Bot\'s', ['count' => $count]);
                            $channel->guild->channels->save($channel);
                        });
                        break;

                    case 'online_users':
                        $channel->guild->members->freshen()->done(function (MemberRepository $memberRepository) use ($channel) {
                            $count = 0;

                            /** @var Member $member */
                            foreach ($memberRepository as $member) {
                                if ($member->status == 'online') {
                                    $count++;
                                }
                            }

                            $channel->name = _text($channel->guild, ':count Online Members', ['count' => $count]);
                            $channel->guild->channels->save($channel);
                        });
                        break;
                }
            });
        }
    }
}
