<?php

namespace App\Tulpar\Events\Guild\Role;

use Discord\Discord;
use Discord\Parts\Guild\Role;

class CreateEvent
{
    public function __invoke(Role $role, Discord $discord)
    {
        // ...
    }
}
