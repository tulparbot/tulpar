<?php

namespace App\Tulpar\Events\Guild;

use Discord\Discord;
use Discord\Parts\Guild\Guild;

class UpdateEvent
{
    public function __invoke(Guild $guild, Discord $discord, Guild $old)
    {
        // ...
    }
}
