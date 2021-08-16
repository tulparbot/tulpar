<?php

namespace App\Tulpar\Timers;

use App\Models\Job;
use React\EventLoop\TimerInterface;

class JobTimer
{
    public static function run(TimerInterface $timer)
    {
        foreach (Job::where('executed', false)->get() as $job) {
            $job->run();
        }
    }
}
