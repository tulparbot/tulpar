<?php

namespace App\Tulpar\Events\Message\Reaction;

use Discord\Discord;

class RemoveEmojiEvent
{
    public function __invoke($reaction, Discord $discord)
    {
        // ...
    }
}
