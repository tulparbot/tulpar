<?php


namespace App\Tulpar\Commands\Basic;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use Discord\Parts\Embed\Embed;

class HelpCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'help';

    public static string $description = 'Show the all commands and usages.';

    public static array $permissions = [];

    public function run(): void
    {
        $embed = new Embed($this->discord);
        $embed->title = config('app.name') . ' - ' . config('app.version');

        /** @var BaseCommand $command */
        foreach (config('tulpar.commands', []) as $command) {
            if ((new $command($this->message, $this->discord))->checkAccess()) {
                $embed->addFieldValues($command::getCommand(), $command::getHelp());
            }
        }

        $this->message->channel->sendEmbed($embed);
    }
}
