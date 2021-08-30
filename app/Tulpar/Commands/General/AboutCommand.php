<?php


namespace App\Tulpar\Commands\General;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Guild\Guild;
use Illuminate\Support\Carbon;

class AboutCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'about';

    public static string $description = 'Show about the bot.';

    public static array $permissions = [];

    public static string $version = '1.1';

    public static bool $allowPm = true;

    public static string $category = CommandCategory::General;

    public function run(): void
    {
        $version = config('app.version');
        $memory_usage = memory_get_usage(true);
        $guilds = $this->discord->guilds->count();
        $users = 0;
        $runtime = Carbon::now()->addSeconds(intval(microtime(true) - TULPAR_START))->diffForHumans();

        /** @var Guild $guild */
        foreach ($this->discord->guilds as $guild) {
            $users += $guild->member_count;
        }

        $embed = new Embed($this->discord);
        $embed->setThumbnail($this->discord->avatar);
        $embed->setFooter($this->translate('Bot ID: :id - Runtime: :runtime', [
            'id' => $this->discord->id,
            'runtime' => $runtime,
        ]));

        $embed->addFieldValues($this->translate('Version'), $version);

        $embed->addFieldValues($this->translate('Server Count'), $guilds, true);
        $embed->addFieldValues($this->translate('User Count'), $users, true);
        $embed->addFieldValues($this->translate('Latency'), PingCommand::ping() . 'ms', true);

        $embed->addFieldValues($this->translate('Memory Usage'), bytesToHuman($memory_usage), true);
        $embed->addFieldValues($this->translate('Creation Date'), Carbon::make('8/12/2021')->toDateString(), true);
        $embed->addFieldValues($this->translate('Author'), Helpers::userTag('569169824056475679'), true);

        $builder = MessageBuilder::new();
        $builder->addEmbed($embed);

        $builder->addComponent(ActionRow::new()
            ->addComponent(Button::new(Button::STYLE_LINK)->setEmoji('🧑‍💻')->setLabel('Developer')->setUrl('https://isaeken.com.tr'))
            ->addComponent(Button::new(Button::STYLE_LINK)->setEmoji('☠️')->setLabel('Tulpar Official')->setUrl('https://tulpar.xyz')));

        $builder->addComponent(ActionRow::new()
            ->addComponent(Button::new(Button::STYLE_LINK)->setEmoji('🎩')->setLabel('Web Mafyası')->setUrl('https://webmafyasi.net'))
            ->addComponent(Button::new(Button::STYLE_LINK)->setEmoji('🌍')->setLabel('Host Adresim')->setUrl('https://hostadresim.net')));

        $this->message->channel->sendMessage($builder);
    }
}
