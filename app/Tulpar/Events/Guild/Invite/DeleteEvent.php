<?php

namespace App\Tulpar\Events\Guild\Invite;

use Discord\Discord;

class DeleteEvent
{
    public function __invoke($invite, Discord $discord)
    {
        // ...
    }
}
