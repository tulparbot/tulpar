<?php

namespace App\Tulpar\Events\Guild\Role;

use Discord\Discord;
use Discord\Parts\Guild\Role;

class UpdateEvent
{
    public function __invoke(Role $role, Discord $discord, Role $old)
    {
        // ...
    }
}
