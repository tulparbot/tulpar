<?php

namespace App\Tulpar\Timers;

use App\Tulpar\Tulpar;
use Discord\Parts\User\Activity;
use Illuminate\Support\Str;
use React\EventLoop\TimerInterface;

class ActivityTimer
{
    public static function run(TimerInterface $timer)
    {
        $activities = config('tulpar.activities');
        $activity = $activities[array_rand($activities)];
        $activity->name = Str::of($activity->name)
            ->replace('{prefix}', Tulpar::getPrefix())
            ->replace('{guild_count}', Tulpar::getInstance()->getDiscord()->guilds->count())
            ->replace('{member_count}', Tulpar::getInstance()->getDiscord()->users->count())
            ->replace('{command_count}', count(config('tulpar.commands')));

        /** @var Activity $_ */
        $_ = Tulpar::getInstance()->getDiscord()->factory(Activity::class, $activity);
        Tulpar::getInstance()->getDiscord()->updatePresence($_);
    }
}
