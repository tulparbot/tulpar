<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Tulpar\Tulpar;
use Discord\Exceptions\IntentException;
use Discord\WebSockets\Intents;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Symfony\Component\Console\Command\Command as CommandAlias;

class RunCommand extends Command
{
    /**
     * @var bool $restartReceived
     */
    public static bool $restartReceived = true;

    /**
     * @var Tulpar|null $instance
     */
    public static Tulpar|null $instance = null;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'run';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start the bot';

    /**
     * @throws IntentException
     */
    public function makeInstance(): void
    {
        static::$instance = Tulpar::newInstance();
        static::$instance->options['token'] = (string)config('discord.token');
        static::$instance->options['loadAllMembers'] = true;
        static::$instance->options['intents'] = Intents::getAllIntents();
        static::$instance->getDiscord()->getLoop()->addPeriodicTimer(3, function () {
            foreach (Job::where('executed', false)->get() as $job) {
                $job->run();
            }
        });
        static::$instance->getClient()->linkDiscord(static::$instance->getDiscord());
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        while (static::$restartReceived) {
            static::$restartReceived = false;

            try {
                $this->makeInstance();
                static::$instance->run($this->getOutput());
                static::$instance->stop();
            } catch (IntentException | Exception $exception) {
                $this->error($exception);
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
