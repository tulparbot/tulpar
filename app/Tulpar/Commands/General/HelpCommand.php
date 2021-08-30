<?php


namespace App\Tulpar\Commands\General;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Guard;
use App\Tulpar\Helpers;
use App\Tulpar\Tulpar;
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

    public static string $version = '1.4';

    public static bool $allowPm = true;

    public static string $category = CommandCategory::General;

    public function run(): void
    {
        $categories = [];
        foreach (config('tulpar.categories') as $key => $value) {
            $categories[$key] = $value;
        }

        $builder = MessageBuilder::new();
        $builder->setReplyTo($this->message);

        $selectMenu = SelectMenu::new();
        foreach ($categories as $key => $category) {
            $option = Option::new($category->name, $key);
            if ($category->emoticon) {
                $option->setEmoji($category->emoticon);
            }

            $selectMenu = $selectMenu
                ->addOption($option)
                ->setListener(function (Interaction $interaction, Collection $options) use ($categories) {
                    if ($interaction->member->id == $this->message->member->id) {
                        /** @var string $key */
                        $key = $options[0]->getValue();

                        if (!array_key_exists($key, $categories)) {
                            $interaction->updateMessage(MessageBuilder::new()->setContent('Invalid Category'));
                            return;
                        }

                        $category = $categories[$key];
                        $builder = MessageBuilder::new()->setContent('Commands of in the category: ' . $category->name);
                        $embeds = [];

                        if (isset($category->commands)) {
                            /** @var BaseCommand $command */
                            foreach ($category->commands as $command) {
                                if (Guard::canUseCommand($command, $this->message->member)) {
                                    $embed = new Embed($this->discord);
                                    $embed->setAuthor(
                                        Tulpar::getPrefix($this->message->guild_id) . $command::getCommand(),
                                        $this->discord->user->getAvatarAttribute(),
                                    );
                                    $embed->setThumbnail($this->message->user->avatar);
                                    $content = '';

                                    if (mb_strlen($command::getUsages()) > 0) {
                                        foreach ($command::$usages as $usage) {
                                            if (mb_strlen($usage) > 0) {
                                                $content .= Helpers::line(Tulpar::getPrefix($this->message->guild_id) . $command::getCommand() . ' ' . $usage) . PHP_EOL;
                                            }
                                            else {
                                                $content .= Helpers::line(Tulpar::getPrefix($this->message->guild_id) . $command::getCommand()) . PHP_EOL;
                                            }
                                        }
                                    }
                                    else {
                                        $content = Tulpar::getPrefix($this->message->guild_id) . $command::getCommand();
                                    }

                                    $content = '```' . $content . '```';
                                    if (mb_strlen($command::getDescription()) > 0) {
                                        $content .= PHP_EOL . _text($this->message->guild, $command::getDescription());
                                    }

                                    $embed->setDescription($content);
                                    $embed->setFooter($command::getVersion());

                                    $embeds[] = $embed;
                                }
                            }
                        }

                        if (count($embeds) < 1) {
                            $builder->setContent('No command found in this category for you.');
                        }
                        else {
                            $builder->setEmbeds($embeds);
                        }

                        $interaction->updateMessage($builder);
                    }
                }, $this->discord);
        }

        $builder->setContent('Help menu for user: ' . Helpers::userTag($this->message->member->id));
        $builder->addComponent($selectMenu);
        $this->message->channel->sendMessage($builder);
    }
}
