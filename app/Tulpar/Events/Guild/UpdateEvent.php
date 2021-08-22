<?php

namespace App\Tulpar\Events\Guild;

use App\Support\Str;
use Discord\Discord;
use Discord\Parts\Guild\Guild;
use Illuminate\Support\Facades\Cache;

class UpdateEvent
{
    public function __invoke(Guild $guild, Discord $discord, Guild $old)
    {
        foreach (cache()->getKeys() as $key) {
            if (Str::startsWith($key, 'user-guilds-')) {
                Cache::forget($key);
            }
        }
    }
}
