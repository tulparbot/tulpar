<?php

namespace App\Tulpar\Events\Channel;

use Discord\Discord;
use Discord\Parts\Channel\Channel;

class DeleteEvent
{
    public function __invoke(Channel $channel, Discord $discord)
    {
        // ...
    }
}
