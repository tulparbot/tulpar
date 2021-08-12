<?php


namespace App\Tulpar\Commands\Basic;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use Discord\Builders\Components\Option;
use Discord\Builders\Components\SelectMenu;
use Discord\Builders\MessageBuilder;
use Discord\Helpers\Collection;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Interaction;

class HelpCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'help';

    public static string $description = 'Show the all commands and usages.';

    public static array $permissions = [];

    public static string $version = '1.1';

    public static bool $allowPm = true;

    public function run(): void
    {
        $embed = new Embed($this->discord);
        $embed->title = config('app.name') . ' ' . config('app.version');
        $embed->description = 'You can use these commands in here.';

        $builder = MessageBuilder::new();
        $builder->setContent('Select a command to show help.');
        $builder->setReplyTo($this->message);

        $selectMenu = SelectMenu::new();
        /** @var BaseCommand $command */
        foreach (config('tulpar.commands', []) as $command) {
            if ((new $command($this->message, $this->discord))->checkAccess()) {
                $selectMenu = $selectMenu->addOption(Option::new($command::getCommand(), $command::getCommand()))->setListener(function (Interaction $interaction, Collection $options) use ($command) {
                    $embed = new Embed($this->discord);
                    $embed->setAuthor($this->message->user->username, $this->message->user->avatar);
                    $embed->setDescription($command::getHelp());

                    $interaction->updateMessage(MessageBuilder::new()->setContent('Command details:')->addEmbed($embed));
                }, $this->discord);
            }
        }

        $builder->addComponent($selectMenu);
        $this->message->channel->sendMessage($builder);
    }
}
