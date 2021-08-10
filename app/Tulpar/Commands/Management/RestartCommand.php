<?php


namespace App\Tulpar\Commands\Management;


use App\Console\Commands\StartCommand;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Tulpar;

class RestartCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'restart';

    public static string $description = 'Stop and terminate bot processes and restart.';

    public static array $permissions = ['root'];

    public static array $usages = ['', '--reason="reason"', '--time=1'];

    public static bool $allowPm = true;

    public static string $version = '1.1';

    private function restart()
    {
        StartCommand::$restartReceived = true;
        sleep(1);

        Tulpar::getInstance()->getDiscord()->close(false);
        sleep(1);

        proc_open(
            PHP_BINARY . ' ' . base_path('tulpar') . ' start',
            [STDIN, STDOUT, STDERR],
            $pipes
        );

        exit;
    }

    public function run(): void
    {
        $time = $this->userCommand->hasOption('time') ? intval($this->userCommand->getOption('time')) : 0;
        $reason = $this->userCommand->hasOption('reason') ? $this->userCommand->getOption('reason') : '';
        $message = config('app.name') . ' is restarting...';

        if ($time > 0 && $reason != '') {
            $message = config('app.name') . ' is restarting in ' . $time . ' seconds because: ' . $reason;
        }
        else if ($time > 0) {
            $message = config('app.name') . ' is restarting in ' . $time . ' seconds';
        }
        else if ($reason != '') {
            $message = config('app.name') . ' is restarting because: ' . $reason;
        }

        $this->message->reply($message)->done(function () use ($time) {
            sleep($time);
            $this->restart();
        });
    }
}
