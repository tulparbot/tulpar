<?php

namespace App\Tulpar\Events\Message;

use Discord\Discord;
use Discord\Helpers\Collection;
use stdClass;

class DeleteBulkEvent
{
    public function __invoke(Collection|stdClass|array $messages, Discord $discord)
    {
        // ...
    }
}
