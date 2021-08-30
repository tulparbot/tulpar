<?php

namespace App\Tulpar\Commands\Development;

use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Timers\CleanStorageTimer;

class ClearFileSystemCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'clear-file-system';

    public static string $description = 'Clear the file system.';

    public static array $permissions = ['root'];

    public static bool $allowPm = true;

    public static string $category = CommandCategory::Development;

    public function run(): void
    {
        CleanStorageTimer::run(null);
        $this->message->reply('File system cleared.');
    }
}
