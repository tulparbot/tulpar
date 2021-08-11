<?php

namespace App\Tulpar\Events\General;

use Discord\Discord;
use Discord\Parts\WebSockets\PresenceUpdate;

class PresenceUpdateEvent
{
    public function __invoke(PresenceUpdate $presence, Discord $discord)
    {
        // ...
    }
}
