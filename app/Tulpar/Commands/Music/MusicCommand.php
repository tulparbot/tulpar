<?php


namespace App\Tulpar\Commands\Music;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use Illuminate\Support\Str;

class MusicCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'music';

    public static string $description = 'Music commands.';

    public static array $permissions = [];

    public static array $requires = [0];

    public static string $category = CommandCategory::Music;

    public function run(): void
    {
        $message = $this->message;
        $discord = $this->discord;
        $message->content = Str::of($this->message->content)->substr(mb_strlen('music ' . $this->userCommand->getArgument(0)));

        switch (mb_strtolower($this->userCommand->getArgument(0))) {
            case 'play':
                (new PlayCommand($message, $discord))->run();
                return;

            default:
                $this->message->channel->sendMessage($this->getHelp());
        }
    }
}
