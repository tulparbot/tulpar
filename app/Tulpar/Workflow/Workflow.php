<?php

namespace App\Tulpar\Workflow;

use Discord\Discord;
use Discord\Parts\Channel\Message;

class Workflow
{
    /**
     * @param array   $workflow
     * @param Message $message
     * @param Discord $discord
     */
    public function __construct(public array $workflow, public Message $message, public Discord $discord)
    {
        // ...
    }

    public function run(): void
    {
        $works = collect($this->workflow)->sortKeys();

        if (!(count($works) > 1)) {
            $works->first()->run($this->message, $this->discord);
        }
        else {
            $index = 0;

            /** @var Work $work */
            foreach ($works as $item => $work) {
                if (isset($works[$index + 1])) {
                    $work->then = function () use ($works, $index) {
                        /** @var Work $next */
                        $next = $works[$index + 1];
                        $next->run($this->message, $this->discord);
                    };
                }

                $works[$item] = $work;
                $index++;
            }

            /** @var Work $work */
            $work = collect($works)->first();
            $work->run($this->message, $this->discord);
        }

        /** @var Work $work */
        foreach (collect($this->workflow)->sortKeys() as $work) {
            $work->run($this->message, $this->discord);
        }
    }
}
