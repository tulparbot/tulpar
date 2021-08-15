<?php


namespace App\Tulpar\Commands\Game;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;

class HeadsTailsCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'hot';

    public static string $description = 'Heads or Tails.';

    public static array $permissions = [];

    public static string $version = '1.0';

    public static bool $allowPm = true;

    public static string $category = CommandCategory::Game;

    public function run(): void
    {
        $head = '💵';
        $tail = '💰';
        $this->message->reply(rand(0, 1) == 1 ? $head : $tail);
    }
}
