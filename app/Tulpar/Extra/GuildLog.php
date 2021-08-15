<?php

namespace App\Tulpar\Extra;

use App\Enums\LogLevel;
use App\Models\Server;
use App\Tulpar\Tulpar;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Guild\Guild;

class GuildLog
{
    public static function log(Guild|string $guild, string $message, string $level = LogLevel::Info): void
    {
        if (!$guild instanceof Guild) {
            $guild = Tulpar::getInstance()->getDiscord()->guilds->get('id', $guild);
        }

        $server = Server::where('server_id', $guild->id)->first();
        $channel = $server->log_channel;

        if ($channel == null) {
            return;
        }

        $guild->channels->fetch($channel)->done(function (Channel $channel) use ($message, $level) {
            $channel->sendMessage(mb_strtoupper($level) . ': ' . $message);
        });
    }
}
