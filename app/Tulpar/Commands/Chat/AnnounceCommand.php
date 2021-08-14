<?php


namespace App\Tulpar\Commands\Chat;


use App\Enums\CommandCategory;
use App\Support\Str;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Tulpar;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Guild\Guild;

class AnnounceCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'announce';

    public static string $description = 'Announce to all servers announcement channel.';

    public static array $usages = [
        'announcement...',
    ];

    public static array $permissions = ['root'];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Chat;

    public function run(): void
    {
        $command = static::getCommand();
        if (mb_strlen($this->message->content) < mb_strlen($command) + 5) {
            $this->message->reply(static::getHelp());
            return;
        }

        $message = Str::of($this->message->content)->substr(mb_strlen($command) + 2);
        $channels = [];
        $guilds = [];

        $send = function (Channel $channel) use ($message) {
            $embed = new Embed($this->discord);
            $embed->setAuthor('New Announcement From ' . $this->discord->username, $this->discord->avatar);
            $embed->setDescription($message);
            $embed->setFooter(now() . ' - ' . $this->message->user->username);
            $channel->sendEmbed($embed);
        };

        /** @var Guild $guild */
        foreach (Tulpar::getInstance()->getDiscord()->guilds as $guild) {
            /** @var Channel $channel */
            foreach ($guild->channels as $channel) {
                if ($channel->type == 5) {
                    $guilds[] = $guild->id;
                    $channels[] = $channel->id;
                    $send($channel);
                }
            }
        }

        $this->message->reply('Announcement sent to ' . collect($channels)->unique()->count() . ' in ' . collect($guilds)->unique()->count() . ' servers.');
    }
}
