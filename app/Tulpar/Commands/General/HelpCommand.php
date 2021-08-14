<?php


namespace App\Tulpar\Commands\General;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
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

    public static string $version = '1.3';

    public static bool $allowPm = true;

    public static string $category = CommandCategory::General;

    public function run(): void
    {
        $categories = CommandCategory::getCategories();

        $builder = MessageBuilder::new();
        $builder->setReplyTo($this->message);

        $selectMenu = SelectMenu::new();
        foreach ($categories as $category) {
            $option = Option::new(ucwords($category), $category);
            if (isset(CommandCategory::getCategoryEmojis()[$category])) {
                $option->setEmoji(CommandCategory::getCategoryEmojis()[$category]);
            }

            $selectMenu = $selectMenu->addOption($option)->setListener(function (Interaction $interaction, Collection $options) use ($categories) {
                if ($interaction->member->id == $this->message->member->id) {
                    /** @var string $category */
                    $category = $options[0]->getValue();

                    if (!in_array($category, $categories)) {
                        $interaction->updateMessage(MessageBuilder::new()->setContent('Invalid Category'));
                        return;
                    }

                    $commands = CommandCategory::getCommands()[$category];
                    $builder = MessageBuilder::new()->setContent('Commands of in the category: ' . ucwords($category));

                    /** @var BaseCommand $command */
                    foreach ($commands as $command) {
                        if ((new $command($this->message, $this->discord))->checkAccess()) {
                            if (mb_strlen($command::getUsages()) > 0) {
                                $embed = new Embed($this->discord);
                                $embed->setAuthor(Tulpar::getPrefix($this->message->guild_id) . $command::getCommand(), $this->discord->user->getAvatarAttribute());
                                $embed->setThumbnail($this->message->user->avatar);

                                $content = '';
                                foreach ($command::$usages as $usage) {
                                    if (mb_strlen($usage) > 0) {
                                        $content .= Helpers::line(Tulpar::getPrefix($this->message->guild_id) . $command::getCommand() . ' ' . $usage) . PHP_EOL;
                                    }
                                    else {
                                        $content .= Helpers::line(Tulpar::getPrefix($this->message->guild_id) . $command::getCommand()) . PHP_EOL;
                                    }
                                }

                                $embed->setDescription('```' . $content . '```');
                                $embed->setFooter($command::getVersion());
                                $builder->addEmbed($embed);
                            }
                        }
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
