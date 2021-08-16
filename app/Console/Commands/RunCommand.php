<?php

namespace App\Console\Commands;

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Waiting before starting application...');
        $this->info('Tulpar Discord Bot: Developed by https://github.com/isaeken THX.');
        sleep(3);

        $this->info((static::$restartReceived ? 'Restarting' : 'Starting') . '...');

        while (static::$restartReceived) {
            static::$restartReceived = false;

            try {
                $this->makeInstance();

                $this->info('Starting bot...');
                static::$instance->run($this->getOutput());

                $this->info('Stopping bot...');
                static::$instance->stop();

                $this->info('Bot stopped.');
            } catch (IntentException | Exception $exception) {
                $this->error($exception);
                static::$restartReceived = true;
            }
        }

        $this->info('Developed by https://github.com/isaeken THX');
        return CommandAlias::SUCCESS;
    }

    public function makeInstance(): void
    {
        $this->info('Creating instance...');
        static::$instance = Tulpar::newInstance();

        $this->info('Setting Discord Token...');
        static::$instance->options['token'] = (string)config('discord.token');

        $this->info('Setting intents...');
        static::$instance->options['loadAllMembers'] = true;
        static::$instance->options['intents'] = Intents::getAllIntents();
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
