<?php

namespace App\Tulpar\Events\General;

use Discord\Discord;
use Discord\Parts\WebSockets\TypingStart;

class TypingStartEvent
{
    public function __invoke(TypingStart $typingStart, Discord $discord)
    {
        // ...
    }
}
