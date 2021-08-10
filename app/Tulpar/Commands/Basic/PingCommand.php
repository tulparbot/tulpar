<?php


namespace App\Tulpar\Commands\Basic;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use Discord\Http\Http;
use JJG\Ping;

class PingCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'ping';

    public static string $description = 'Show the bot\'s ping.';

    public static array $permissions = ['root'];

    public function ping(): float
    {
        $ping = new Ping(Http::BASE_URL);
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
        $this->message->channel->sendMessage(sprintf('The %s\'s ping is: %sms', config('app.name'), $this->ping()));
    }
}
