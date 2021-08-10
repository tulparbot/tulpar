<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Tulpar\Tulpar;
use Discord\Exceptions\IntentException;
use Discord\Slash\Parts\Choices;
use Discord\Slash\Parts\Interaction;
use Discord\WebSockets\Intents;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Symfony\Component\Console\Command\Command as CommandAlias;

class StartCommand extends Command
{
    /**
     * @var bool $restartReceived
     */
    public static bool $restartReceived = true;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'start';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start the bot';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        while (static::$restartReceived) {
            static::$restartReceived = false;

            $tulpar = Tulpar::newInstance();
            $tulpar->options['token'] = (string)config('discord.token');
            $tulpar->options['loadAllMembers'] = true;
            $tulpar->options['intents'] = Intents::getAllIntents();

            try {
                $tulpar->getDiscord()->getLoop()->addPeriodicTimer(3, function () {
                    foreach (Job::where('executed', false)->get() as $job) {
                        $job->run();
                    }
                });
                $tulpar->getClient()->linkDiscord($tulpar->getDiscord());

                $tulpar->getClient()->registerCommand('anneler', function (Interaction $interaction, Choices $choices) {
                    dump($choices);
                    $interaction->acknowledge();
                });

                $tulpar->run($this->getOutput());
            } catch (IntentException | Exception $e) {
                $this->error($e->getTraceAsString());
                static::$restartReceived = true;
            }
        }

        return CommandAlias::SUCCESS;
    }

    /**
     * Define the command's schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
