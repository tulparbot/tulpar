<?php


namespace App\Tulpar\Commands\Basic;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;

class AboutCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'about';

    public static string $description = 'Show about page.';

    public static array $permissions = [];

    public function run(): void
    {
        $name = config('app.name');
        $version = config('app.version');

        $message = <<<EOF
About

$name ($version) is PHP based advanced Discord Bot created by İsa Eken.

Features

-
-
-

Connect To Me

https://github.com/isaeken
hello@isaeken.com.tr
EOF;
        $this->message->channel->sendMessage('```' . $message . '```');
    }
}
