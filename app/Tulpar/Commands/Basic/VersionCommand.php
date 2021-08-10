<?php


namespace App\Tulpar\Commands\Basic;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;

class VersionCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'version';

    public static string $description = 'Show bot version.';

    public static array $permissions = [];

    public function run(): void
    {
        $version = config('app.version');
        $this->message->reply('Current version: ``' . $version . '``');
    }
}
