<?php


namespace App\Tulpar\Commands\Music;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use App\Tulpar\Log;
use App\Tulpar\MusicPlayer;
use Discord\Voice\VoiceClient;
use Exception;

class PlayCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'play';

    public static string $description = 'Play the music.';

    public static array $permissions = [];

    public static array $requires = [0];

    public static string $category = CommandCategory::Music;

    /**
     * @throws Exception
     */
    public function play(VoiceClient $client): VoiceClient
    {
        $this->message->channel->sendMessage('Downloading & adding queue song: ')->done(function () use ($client) {
            MusicPlayer::get($client->getChannel()->guild)
                ->addQueue($this->userCommand->getArgument(0))
                ->play($client, function ($voiceClient, $filename, $song) use ($client) {
                    $this->message->channel->sendMessage($this->userCommand->string);
                    dump($this->userCommand->string);
                    $this->message->channel->sendMessage(json_encode($song));
                    NowPlayingCommand::show($client->getChannel());
                })
                ->save();
        });

        return $client;
    }

    public function run(): void
    {
        $channel = Helpers::getMemberVoiceChannel($this->message->member);

        if ($channel == null) {
            $this->message->channel->sendMessage('You are not in the voice channel.');
            return;
        }

        $joinVoiceChannel = function () use ($channel) {
            $this->discord->joinVoiceChannel($channel)->done(function (VoiceClient $client) {
                return $this->play($client);
            }, function ($exception) {
                Log::critical($exception);
            });
        };

        $voiceChannel = $this->discord->getVoiceClient($channel->guild_id);
        if ($voiceChannel != null) {
            $voiceChannel->pause();
            $voiceChannel->close();
        }

        $joinVoiceChannel();
    }
}
