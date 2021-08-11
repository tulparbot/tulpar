<?php

namespace App\Tulpar\Events\Message;

use Discord\Discord;
use Discord\Helpers\Collection;

class DeleteBulkEvent
{
    public function __invoke(Collection $messages, Discord $discord)
    {
        // ...
    }
}
