<?php

namespace App\Tulpar\Commands\Other;

use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use GuzzleHttp\Client;

class ExchangeCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'exchange';

    public static string $description = 'Show exchange rates.';

    public static array $permissions = [];

    public static bool $allowPm = true;

    public static string $category = CommandCategory::General;

    public static array $requires = [0, 1];

    public static array $usages = [
        'from to',
    ];

    public function run(): void
    {
        $from = mb_strtoupper($this->userCommand->getArgument(0));
        $to = mb_strtoupper($this->userCommand->getArgument(1));
        $rate = static::rate($from, $to);

        if ($from == $to) {
            $this->message->reply('You can\'t do this.');
            return;
        }

        if ($rate == 0) {
            $this->message->reply('A error occurred.');
            return;
        }

        $this->message->reply('1 ' . $from . ' is ' . round($rate, 2) . ' ' . $to);
    }

    public static function rate(string $from, string $to): float
    {
        $from = mb_strtolower($from);
        $to = mb_strtolower($to);
        $return = 0.0;
        $client = new Client;
        $response = json_decode($client->get('https://api.isaeken.com/v1/currencies/' . $from . '/' . $to)->getBody()->getContents());
        if ($response->success) {
            $return = floatval($response->content->rate);
        }

        return $return;
    }
}