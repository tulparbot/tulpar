<?php

namespace App\Tulpar\Extra;

use Discord\Parts\Guild\Guild;
use Discord\Voice\VoiceClient;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use React\Promise\Promise;

class MusicPlayer
{
    /**
     * @var array $players
     */
    public static array $players = [];

    /**
     * @param Guild|string $guild
     * @return MusicPlayer
     */
    public static function get(Guild|string $guild): MusicPlayer
    {
        if ($guild instanceof Guild) {
            $guild = $guild->id;
        }

        if (array_key_exists($guild, static::$players)) {
            return static::$players[$guild];
        }

        return new MusicPlayer($guild);
    }

    /**
     * @var string $guild
     */
    public string $guild;

    /**
     * @var bool $playing
     */
    public bool $playing = false;

    /**
     * @param Guild|string $guild
     * @param array        $queue
     * @param object|null  $nowPlaying
     */
    public function __construct(Guild|string $guild, public array $queue = [], public object|null $nowPlaying = null)
    {
        if ($guild instanceof Guild) {
            $this->guild = $guild->id;
        }
    }

    /**
     * @param string $query
     * @return $this
     */
    public function addQueue(string $query): static
    {
        $search = Youtube::search($query, max_result: 1)->first();
        if ($search !== null) {
            $this->queue[] = (object)[
                'id' => $search->id->videoId,
                'title' => $search->snippet->title,
                'description' => $search->snippet->description,
                'thumbnail' => $search->snippet->thumbnails->medium->url,
            ];
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function next(): static
    {
        array_shift($this->queue);
        return $this;
    }

    /**
     * @param VoiceClient   $voiceClient
     * @param callable|null $before
     * @param callable|null $after
     * @return $this
     */
    public function play(VoiceClient $voiceClient, callable|null $before = null, callable|null $after = null): static
    {
        new Promise(function () use ($voiceClient, $before, $after) {
            try {
                $song = collect($this->queue)->first();
                if ($song === null) {
                    return;
                }

                $filename = Cache::remember($song->id, Carbon::make('+1 years'), function () use ($song) {
                    return Youtube::download($song->id);
                });

                $this->playing = true;
                if ($before !== null) {
                    $before($voiceClient, $filename, $song);
                }

                $voiceClient->playFile($filename)->done(function () use ($voiceClient, $before, $after, $filename, $song) {
                    $this->playing = false;
                    $this->next();
                    $this->save();

                    if ($after !== null) {
                        $after($voiceClient, $filename, $song);
                    }

                    // $voiceClient->pause();
                });
            } catch (Exception $exception) {
                dd($exception);
            }
        });

        return $this;
    }

    /**
     * @return $this
     */
    public function save(): static
    {
        static::$players[$this->guild] = $this;
        return $this;
    }
}
