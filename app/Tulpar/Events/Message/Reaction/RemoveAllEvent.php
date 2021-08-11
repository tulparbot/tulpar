<?php

namespace App\Tulpar\Events\Message\Reaction;

use Discord\Discord;
use Discord\Parts\WebSockets\MessageReaction;

class RemoveAllEvent
{
    public function __invoke(MessageReaction $reaction, Discord $discord)
    {
        // ...
    }
}
