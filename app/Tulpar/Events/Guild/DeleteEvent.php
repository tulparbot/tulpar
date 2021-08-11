<?php

namespace App\Tulpar\Events\Guild;

use Discord\Discord;
use Discord\Parts\Guild\Guild;

class DeleteEvent
{
    public function __invoke(Guild $guild, Discord $discord, bool $unavailable)
    {
        // ...
    }
}
