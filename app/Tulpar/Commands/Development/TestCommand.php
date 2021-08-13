<?php

namespace App\Tulpar\Commands\Development;

use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;

class TestCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'test';

    public static string $description = 'The testing command for development.';

    public static array $permissions = ['root'];

    public static bool $allowPm = true;

    public static string $category = CommandCategory::Development;

    public function run(): void
    {
        // ...
    }
}
