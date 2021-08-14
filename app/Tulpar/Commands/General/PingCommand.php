<?php


namespace App\Tulpar\Commands\General;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use JJG\Ping;

class PingCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'ping';

    public static string $description = 'Show the bot\'s ping.';

    public static array $permissions = [];

    public static string $version = '1.1';

    public static array $usages = [];

    public static bool $allowPm = true;

    public static string $category = CommandCategory::General;

    public static function ping(): float
    {
        $ping = new Ping('discord.com');
        $ping->setTtl(128);
        $ping->setTimeout(5);
        $ms = $ping->ping();

        if ($ms === false) {
            return -1;
        }

        return $ms;
    }

    public function run(): void
    {
        $this->message->reply(sprintf(
            'The %s\'s ping is: %sms',
            config('app.name'),
            static::ping(),
        ));
    }
}
