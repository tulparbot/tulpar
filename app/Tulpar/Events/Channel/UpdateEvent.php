<?php

namespace App\Tulpar\Events\Channel;

use Discord\Discord;
use Discord\Parts\Channel\Channel;

class UpdateEvent
{
    public function __invoke(Channel $new, Discord $discord, Channel $old)
    {
        // ...
    }
}
