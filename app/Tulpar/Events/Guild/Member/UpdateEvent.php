<?php

namespace App\Tulpar\Events\Guild\Member;

use Discord\Discord;
use Discord\Parts\User\Member;

class UpdateEvent
{
    public function __invoke(Member $member, Discord $discord, $old)
    {
        // ...
    }
}
