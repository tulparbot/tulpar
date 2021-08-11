<?php

namespace App\Tulpar\Events\Guild\Invite;

use Discord\Discord;
use Discord\Parts\Guild\Invite;

class CreateEvent
{
    public function __invoke(Invite $invite, Discord $discord)
    {
        // ...
    }
}
