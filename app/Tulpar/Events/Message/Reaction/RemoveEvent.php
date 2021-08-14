<?php

namespace App\Tulpar\Events\Message\Reaction;

use App\Tulpar\Commands\General\GiveawayCommand;
use Discord\Discord;
use Discord\Parts\WebSockets\MessageReaction;

class RemoveEvent
{
    public function __invoke(MessageReaction $reaction, Discord $discord)
    {
        if (isset(GiveawayCommand::$votes[$reaction->message_id])) {
            $search = array_search($reaction->user_id, GiveawayCommand::$votes[$reaction->message_id]);
            if ($search !== false) {
                unset(GiveawayCommand::$votes[$reaction->message_id][$search]);
            }
        }
    }
}
