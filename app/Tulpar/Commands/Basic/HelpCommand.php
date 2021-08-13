<?php


namespace App\Tulpar\Commands\Basic;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Builders\Components\Option;
use Discord\Builders\Components\SelectMenu;
use Discord\Builders\MessageBuilder;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Interaction;
use Exception;

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
        $builder->setReplyTo($this->message);

        $count = 0;

        $selectMenu = SelectMenu::new();
        /** @var BaseCommand $command */
        foreach (config('tulpar.commands', []) as $command) {
            if ((new $command($this->message, $this->discord))->checkAccess()) {
                $selectMenu = $selectMenu->addOption(Option::new($command::getCommand(), $command::getCommand()))->setListener(function (Interaction $interaction, Collection $options) use ($command) {
                    if ($interaction->member->id == $this->message->member->id) {
                        $embed = new Embed($this->discord);
                        $embed->setAuthor($this->message->user->username, $this->message->user->avatar);
                        $embed->setDescription($command::getHelp());

                        $interaction->updateMessage(MessageBuilder::new()->setContent('Command details:')->addEmbed($embed));
                    }
                }, $this->discord);

                $count++;
                if ($count > 24) {
                    break;
                }
            }
        }

        try {
            if ($count > 24) {
                $text = '';

                foreach (config('tulpar.commands', []) as $command) {
                    if ((new $command($this->message, $this->discord))->checkAccess()) {
                        $text .= $command::getHelp() . PHP_EOL . PHP_EOL;
                    }
                }

                if (mb_strlen($text) > 2000) {
                    $messages = explode("*/*/*", chunk_split($text, 2000, '*/*/*'));
                    $this->message->reply($messages[0])->done(function (Message $message) use ($messages) {
                        $messages = collect($messages)->except(0)->toArray();
                        foreach ($messages as $msg) {
                            if (mb_strlen($msg) > 0) {
                                $message->reply($msg);
                            }
                        }
                    });

                    return;
                }

                $builder->setContent($text);
            }
            else {
                $builder->setContent('Help menu for user: ' . Helpers::userTag($this->message->member->id));
                $builder->addComponent($selectMenu);
            }

            $this->message->channel->sendMessage($builder);
        } catch (Exception $exception) {
            dd($exception);
        }
    }
}
