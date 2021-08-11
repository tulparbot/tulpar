<?php

namespace App\Tulpar\Events\Channel;

use Discord\Discord;

class PinsUpdateEvent
{
    public function __invoke($pins, Discord $discord)
    {
        // ...
    }
}
