<?php


namespace App\Tulpar\Commands\Development;


use App\Console\Commands\RunCommand;
use App\Enums\CommandCategory;
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

    public static string $category = CommandCategory::Management;

    private function restart(bool $hard = false)
    {
        if ($hard) {
            RunCommand::$restartReceived = false;
            Tulpar::getInstance()->stop();
            sleep(1);

            $process = proc_open(
                PHP_BINARY . ' ' . base_path('tulpar') . ' run',
                [STDIN, STDOUT, STDERR],
                $pipes
            );

            if (is_resource($process)) {
                stream_set_blocking($pipes[0], true);
            }

            return;
        }

        RunCommand::$restartReceived = true;
        Tulpar::getInstance()->stop();
        sleep(1);
    }

    public function run(): void
    {
        $hard = $this->userCommand->hasFlag('hard');
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

        $this->message->reply($message)->done(function () use ($time, $hard) {
            sleep($time);
            $this->restart($hard);
        });
    }
}
