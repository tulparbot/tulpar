<?php

namespace App\Tulpar\Timers;

use App\Models\TwitchConnection;
use App\Tulpar\Commands\Other\TwitchCommand;
use App\Tulpar\Tulpar;
use Discord\Builders\MessageBuilder;
use React\EventLoop\TimerInterface;

class TwitchTimer
{
    public static function run(TimerInterface $timer)
    {
        $connections = TwitchConnection::where('channel_id', '!=', null)->where('token', '!=', null)->where('accounts', '!=', null)->get();
        foreach ($connections as $connection) {
            $accounts = unserialize($connection->accounts) ?? [];
            if (count($accounts) > 0) {
                $channel = Tulpar::getInstance()->getDiscord()->getChannel($connection->channel_id);
                if ($channel != null) {
                    foreach ($accounts as $account) {
                        $status = TwitchCommand::getStatusFromUsername($account, $connection->token);
                        if ($status->live) {
                            $builder = MessageBuilder::new();
                            $builder = TwitchCommand::makeStatusMessage($builder, $status);
                            $channel->sendMessage($builder);
                        }
                    }
                }
            }
        }
    }
}
