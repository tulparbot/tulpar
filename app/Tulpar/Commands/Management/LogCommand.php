<?php


namespace App\Tulpar\Commands\Management;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Log;

class LogCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'log';

    public static string $description = 'Send a log text to main log channel.';

    public static array $usages = [
        '"emergency|alert|critical|error|warning|notice|info|debug" "Debug Message"',
    ];

    public static array $permissions = ['root'];

    public static array $requires = [0, 1];

    public static bool $allowPm = true;

    public static string $version = '1.1';

    public static string $category = CommandCategory::Management;

    public function run(): void
    {
        $levels = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];
        $level = $this->userCommand->getArgument(0);
        $message = $this->userCommand->getArgument(1);

        if (!in_array($level, $levels)) {
            $this->message->reply(static::getHelp());
            return;
        }

        $this->message->reply('Log sent.');
        Log::log($level, $message);
    }
}
