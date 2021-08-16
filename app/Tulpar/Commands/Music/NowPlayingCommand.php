<?php


namespace App\Tulpar\Commands\Music;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Extra\MusicPlayer;
use App\Tulpar\Tulpar;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Embed\Embed;

class NowPlayingCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'np';

    public static string $description = 'Show now playing song.';

    public static array $permissions = [];

    public static string $category = CommandCategory::Music;

    public static function show(Channel|string $channel)
    {
        $discord = Tulpar::getInstance()->getDiscord();

        if (!$channel instanceof Channel) {
            $channel = $discord->getChannel($channel);
        }

        $player = MusicPlayer::get($channel->guild);
        $song = collect($player->queue)->first();

        if (!$player->playing || $song == null) {
            $channel->sendMessage('No songs playing on this server.');
            return;
        }

        $channel->sendEmbed((new Embed($discord))
            ->setTitle($song->title)
            ->setDescription($song->description)
            ->setImage($song->thumbnail));
    }

    public function run(): void
    {
        static::show($this->message->channel);
    }
}
