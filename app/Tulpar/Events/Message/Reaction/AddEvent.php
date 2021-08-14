<?php

namespace App\Tulpar\Events\Message\Reaction;

use App\Tulpar\Commands\General\GiveawayCommand;
use Discord\Discord;
use Discord\Parts\WebSockets\MessageReaction;

class AddEvent
{
    public function __invoke(MessageReaction $reaction, Discord $discord)
    {
        if (!$reaction->user->bot) {
            if (isset(GiveawayCommand::$votes[$reaction->message_id])) {
                GiveawayCommand::$votes[$reaction->message_id][] = $reaction->user_id;
            }
        }
    }
}
