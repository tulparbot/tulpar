<?php


namespace App\Tulpar;


use App\Enums\Align;
use App\Models\Server;
use Closure;
use Discord\Discord;
use Discord\Exceptions\IntentException;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Member;
use Discord\Parts\User\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JetBrains\PhpStorm\Pure;
use React\Promise\PromiseInterface;

class Helpers
{
    /**
     * @param callable|Closure $callable
     * @param                  ...$arguments
     * @return mixed
     */
    public static function call(callable|Closure $callable, ...$arguments): mixed
    {
        try {
            return $callable(...$arguments);
        } catch (Exception $exception) {
            Log::error($exception);
            return $exception;
        }
    }

    /**
     * @param string $line
     * @param string $align
     * @param int    $length
     * @return string
     */
    public static function line(string $line, string $align = Align::Left, int $length = 40): string
    {
        if (mb_strlen($line) > $length) {
            $line = mb_str_split($line, $length);
            $string = '';
            foreach ($line as $part) {
                $string .= static::line($part, $align, $length) . PHP_EOL;
            }

            return $string;
        }

        $string = '';

        if ($align == Align::Right) {
            $__length = $length - mb_strlen($line);
            $string .= str_repeat(' ', $__length);
            $string .= $line;
        }
        else if ($align == Align::Center) {
            $__length = (($length - mb_strlen($line)) / 2);
            $string .= str_repeat(' ', $__length);
            $string .= $line;
            $string .= str_repeat(' ', $__length);
        }
        else {
            $__length = $length - mb_strlen($line);
            $string .= $line;
            $string .= str_repeat(' ', $__length);
        }

        if (mb_strlen($string) < $length) {
            $string .= ' ';
        }

        return $string;
    }

    /**
     * @param string $id
     * @return string
     */
    public static function userTag(string $id): string
    {
        return "<@$id>";
    }

    /**
     * @param User|Member|string $user
     * @return array
     */
    public static function getUserGuilds(User|Member|string $user = '@me'): array
    {
        if ($user instanceof User) {
            $user = $user->username;
        }
        else if ($user instanceof Member) {
            $user = $user->user->username;
        }

        try {
            $request = (new Client(['verify' => false]))
                ->request('get', 'https://discordapp.com/api/v6/users/' . $user . '/guilds', [
                    'headers' => [
                        'Authorization' => 'Bot ' . config('discord.token'),
                    ],
                    'config' => [
                        'curl' => [
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_FOLLOWLOCATION => 1,
                            CURLOPT_SSL_VERIFYPEER => 0,
                        ],
                    ],
                ]);

            if ($request->getStatusCode() === 200) {
                $servers = [];
                foreach (json_decode($request->getBody()->getContents()) as $server) {
                    $server->extra = (object)[
                        'icon' => 'https://cdn.discordapp.com/icons/' . $server->id . '/' . $server->icon . '.webp',
                    ];
                    $server->joinned = ($_ = Server::where('server_id', $server->id)->first()) != null;
                    $server->model = $_;
                    $servers[] = $server;
                }

                return $servers;
            }
        } catch (GuzzleException $e) {
            // ...
        }

        return [];
    }

    /**
     * @param User|string   $user
     * @param Guild         $guild
     * @param callable      $if
     * @param callable|null $else
     * @return PromiseInterface
     * @throws Exception
     */
    public static function whenAdmin(User|string $user, Guild $guild, callable $if, callable $else = null): PromiseInterface
    {
        return static::hasPermission($user, $guild, $if, $else);
    }

    /**
     * @param User|string   $user
     * @param Guild         $guild
     * @param callable      $if
     * @param callable|null $else
     * @param string        $permission
     * @return PromiseInterface
     * @throws Exception
     */
    public static function hasPermission(User|string $user, Guild $guild, callable $if, callable $else = null, string $permission = 'administrator'): PromiseInterface
    {
        return $guild->members
            ->fetch($user instanceof User ? $user->id : $user)
            ->then(function ($user) use ($if, $else, $permission) {
                if ($user->getPermissions()->$permission) {
                    $if();
                }
                else {
                    $else();
                }
            });
    }

    /**
     * @return string[]
     */
    public static function emojis(): array
    {
        return [
            'laughing' => '😆',
            'relaxed' => '☺️',
            'blush' => '😊',
            'upside_down' => '🙃',
            'heart_eyes' => '😍',
            'smiling_face_with_3_hearts' => '🥰',
            'zany_face' => '🤪',
            'stuck_out_tongue_winking_eye' => '😜',
            'stuck_out_tongue_closed_eyes' => '😝',
            'stuck_out_tongue' => '😛',
            'yum' => '😋',
            'face_with_raised_eyebrow' => '🤨',
            'face_with_monocle' => '🧐',
            'nerd' => '🤓',
            'sunglasses' => '🧐',
            'star_struck' => '🤓',
            'partying_face' => '🥳',
            'smirk' => '😏',
            'flushed' => '😳',
            'scream' => '😱',
            'hugging' => '🤗',
            'grimacing' => '😬',
            'dizzy_face' => '😵',
            'cowboy' => '🤠',
            'skull_crossbones' => '☠️',
            'poop' => '💩',
            'heart_eyes_cat' => '😻',
            'thumbsup' => '👍',
            'metal' => '🤘',
            'tongue' => '👅',
            'kiss' => '💋',
            'anatomical_heart' => '🫀',
            'dart' => '🎯',
            'computer' => '💻',
            'heart' => '❤️',
            'black_heart' => '🖤',
            'revolving_hearts' => '💞',
            'sparkling_heart' => '💖',
            'gift_heart' => '💝',
            'heart_on_fire' => '❤️‍🔥',
        ];
    }

    /**
     * @return string
     */
    #[Pure] public static function getRandomEmoticon(): string
    {
        return static::emojis()[array_rand(static::emojis())];
    }

    /**
     * @param Member|string $member_id
     * @return Channel|null
     * @throws IntentException
     */
    public static function getMemberVoiceChannel(Member|string $member_id): Channel|null
    {
        if ($member_id instanceof Member) {
            $member_id = $member_id->id;
        }

        foreach (Tulpar::$voiceChannels as $channelId => $channel) {
            foreach ($channel as $memberId => $member) {
                if ($member_id == $memberId) {
                    return Tulpar::getInstance()->getDiscord()->getChannel($channelId);
                }
            }
        }

        return null;
    }

    /**
     * @param Channel      $channel
     * @param Discord|null $discord
     * @return Channel
     * @throws IntentException
     */
    public static function copyChannel(Channel $channel, Discord $discord = null): Channel
    {
        if ($discord == null) {
            $discord = Tulpar::getInstance()->getDiscord();
        }

        $new = new Channel($discord);
        $new->name = $channel->name;
        $new->type = $channel->type;
        $new->topic = $channel->topic;
        $new->guild_id = $channel->guild_id;
        $new->position = $channel->position;
        $new->is_private = $channel->is_private;
        $new->bitrate = $channel->bitrate;
        $new->recipients = $channel->recipients;
        $new->nsfw = $channel->nsfw;
        $new->user_limit = $channel->user_limit;
        $new->rate_limit_per_user = $channel->rate_limit_per_user;
        $new->icon = $channel->icon;
        $new->owner_id = $channel->owner_id;
        $new->application_id = $channel->application_id;
        $new->parent_id = $channel->parent_id;
        $new->last_pin_timestamp = $channel->last_pin_timestamp;
        $new->rtc_region = $channel->rtc_region;
        $new->video_quality_mode = $channel->video_quality_mode;
        $new->overwrites = $channel->overwrites;
        $new->webhooks = $channel->webhooks;
        $new->scriptData = $channel->scriptData;

        return $new;
    }

    /**
     * @param string       $id
     * @param Discord|null $discord
     * @return Guild|null
     * @throws IntentException
     */
    public static function findGuild(string $id, Discord $discord = null): Guild|null
    {
        if ($discord == null) {
            $discord = Tulpar::getInstance()->getDiscord();
        }

        return $discord->guilds->get('id', $id);
    }
}
