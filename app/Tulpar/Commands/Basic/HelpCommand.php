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

    public static string $version = '1.1';

    public static bool $allowPm = true;

    public static array $usages = [
        '',
        'command-name',
    ];

    public function run(): void
    {
        $embed = new Embed($this->discord);
        $embed->title = config('app.name') . ' ' . config('app.version');
        $embed->description = 'You can use these commands in here.';

        if ($this->userCommand->hasArgument(0)) {
            $commandName = mb_strtolower($this->userCommand->getArgument(0));

            /** @var BaseCommand $command */
            foreach (config('tulpar.commands', []) as $command) {
                if (mb_strtolower($command::getCommand()) == $commandName && (new $command($this->message, $this->discord))->checkAccess()) {
                    $embed->addFieldValues($command::getCommand(), $command::getHelp());
                }
            }

            $this->message->channel->sendEmbed($embed);
            return;
        }

        /** @var BaseCommand $command */
        foreach (config('tulpar.commands', []) as $command) {
            if ((new $command($this->message, $this->discord))->checkAccess()) {
                $embed->addFieldValues($command::getCommand(), $command::getHelp());
            }
        }

        $this->message->channel->sendEmbed($embed);
    }
}
