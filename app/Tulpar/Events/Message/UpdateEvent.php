<?php

namespace App\Tulpar\Events\Message;

use Discord\Discord;
use Discord\Parts\Channel\Message;

class UpdateEvent
{
    public function __invoke(Message $message, Discord $discord, $oldMessage)
    {
        // ...
    }
}
