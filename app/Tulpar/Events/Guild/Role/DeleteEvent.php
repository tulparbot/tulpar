<?php

namespace App\Tulpar\Events\Guild\Role;

use Discord\Discord;
use Discord\Parts\Guild\Role;

class DeleteEvent
{
    public function __invoke(Role $role, Discord $discord)
    {
        // ...
    }
}
