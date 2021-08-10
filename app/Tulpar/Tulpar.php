<?php


namespace App\Tulpar;


use App\Tulpar\Commands\Authorization\RegisterCommand;
use App\Tulpar\Commands\Basic\AboutCommand;
use App\Tulpar\Commands\Basic\EmoticonsCommand;
use App\Tulpar\Commands\Basic\HelloCommand;
use App\Tulpar\Commands\Basic\HelpCommand;
use App\Tulpar\Commands\Basic\InviteCommand;
use App\Tulpar\Commands\Basic\PingCommand;
use App\Tulpar\Commands\Chat\ClearChannelCommand;
use App\Tulpar\Commands\Development\TestCommand;
use App\Tulpar\Commands\Game\HangmanCommand;
use App\Tulpar\Commands\Management\BotCommand;
use App\Tulpar\Commands\Management\CheckAuthorizationCommand;
use App\Tulpar\Commands\Management\LogCommand;
use App\Tulpar\Commands\Management\RestartCommand;
use App\Tulpar\Commands\Management\RootCommand;
use App\Tulpar\Commands\Management\StatisticsCommand;
use App\Tulpar\Commands\Management\StopCommand;
use App\Tulpar\Commands\Moderation\BanCommand;
use App\Tulpar\Commands\Moderation\UnbanCommand;
use App\Tulpar\Commands\Music\MusicCommand;
use App\Tulpar\Commands\Rank\RankCommand;
use App\Tulpar\Events;
use Discord\Discord;
use Discord\Exceptions\IntentException;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Message;
use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Member;
use Discord\Repository\Guild\ChannelRepository;
use Discord\Slash\Client;
use Discord\WebSockets\Event;
use Illuminate\Console\OutputStyle;

class Tulpar
{
    /**
     * @var Tulpar|null $instance
     */
    private static Tulpar|null $instance = null;

    /**
     * @var array $commands
     */
    public static array $commands = [
        TestCommand::class,
        CheckAuthorizationCommand::class,
        StatisticsCommand::class,
        StopCommand::class,
        RestartCommand::class,
        RootCommand::class,
        LogCommand::class,
        BotCommand::class,

        RegisterCommand::class,
        HelloCommand::class,
        AboutCommand::class,
        EmoticonsCommand::class,
        InviteCommand::class,
        ClearChannelCommand::class,
        HelpCommand::class,
        PingCommand::class,

        MusicCommand::class,

        BanCommand::class,
        UnbanCommand::class,

        HangmanCommand::class,

        RankCommand::class,
    ];

    /**
     * @var array $filters
     */
    public static array $filters = [];

    /**
     * @var array $voiceChannels
     */
    public static array $voiceChannels = [];

    /**
     * @return Tulpar
     */
    public static function getInstance(): Tulpar
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @return Tulpar
     */
    public static function newInstance(): Tulpar
    {
        return static::$instance = new static;
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
    public static function getPrefix(): string
    {
        return env('COMMAND_PREFIX', '!');
    }

    /**
     * @param Message|Guild|string $from
     * @param Discord|null         $discord
     * @return Guild|null
     * @throws IntentException
     */
    public static function findGuildFrom(Message|Guild|string $from, Discord $discord = null): Guild|null
    {
        if ($discord == null) {
            $discord = static::$instance->getDiscord();
        }

        if ($from instanceof Message) {
            $from = $from->guild_id;
        }
        else if ($from instanceof Guild) {
            $from = $from->id;
        }

        return collect($discord->guilds->toArray())->where('id', $from)->first();
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
            $discord = static::$instance->getDiscord();
        }

        return collect($discord->guilds->toArray())->where('id', $id)->first();
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
            $discord = static::$instance->getDiscord();
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

        return $new;
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

        foreach (static::$voiceChannels as $channelId => $channel) {
            foreach ($channel as $memberId => $member) {
                if ($member_id == $memberId) {
                    return static::getInstance()->getDiscord()->getChannel($channelId);
                }
            }
        }

        return null;
    }

    public static function freshChannelsMembers(): void
    {
        /** @var Guild $guild */
        foreach (Tulpar::getInstance()->getDiscord()->guilds as $guild) {
            $guild->channels->freshen()->done(function (ChannelRepository $channelRepository) {
            });
        }
    }

    public static function freshFindMemberInChannels(string $member_id, callable|null $callback = null): void
    {
        /** @var Guild $guild */
        foreach (static::getInstance()->getDiscord()->guilds as $guild) {
            $guild->channels->freshen()->done(function (ChannelRepository $channelRepository) use ($member_id, $callback) {
                /** @var Channel $channel */
                foreach ($channelRepository as $channel) {
//                    dump($member_id);
//                    dd($channel->members->fresh(''));
                    $channel->members->fetch($member_id)->done(function (Member $member) use ($channel, $callback) {
                        return $callback($channel, $member);
                    });


//                    dd($channel->);
                }
            });
        }
    }

    /**
     * @return string
     */
    public static function getRandomEmoticon(): string
    {
        return static::emojis()[array_rand(static::emojis())];
    }

    /**
     * @var Discord|null $discord
     */
    private Discord|null $discord = null;

    /**
     * @var Client|null $client
     */
    private Client|null $client = null;

    /**
     * @var array $options
     */
    public array $options = [];

    /**
     * @return Discord
     * @throws IntentException
     */
    public function getDiscord(): Discord
    {
        if ($this->discord === null) {
            $this->discord = new Discord($this->options);
        }

        return $this->discord;
    }

    /**
     * @return Client
     * @throws IntentException
     */
    public function getClient(): Client
    {
        if ($this->client === null) {
            $this->client = new Client([
                'public_key' => env('DISCORD_PUBLIC_KEY'),
                'loop' => $this->getDiscord()->getLoop(),
            ]);
        }

        return $this->client;
    }

    /**
     * @return Channel|null
     * @throws IntentException
     */
    public function getLogChannel(): Channel|null
    {
        return $this->getDiscord()->getChannel(env('LOG_CHANNEL')) ?? null;
    }

    /**
     * @param OutputStyle $output
     * @throws IntentException
     */
    public function run(OutputStyle $output)
    {
        $this->getDiscord()->on('ready', function (Discord $discord) use ($output) {
            Log::debug('Events are registering...');

            // General
            $discord->on(Event::RESUMED, new Events\General\ResumedEvent);
            $discord->on(Event::PRESENCE_UPDATE, new Events\General\PresenceUpdateEvent);
            $discord->on(Event::PRESENCES_REPLACE, new Events\General\PresencesReplaceEvent);
            $discord->on(Event::TYPING_START, new Events\General\TypingStartEvent);
            $discord->on(Event::USER_SETTINGS_UPDATE, new Events\General\UserSettingsUpdateEvent);
            $discord->on(Event::VOICE_STATE_UPDATE, new Events\General\VoiceStateUpdateEvent);
            $discord->on(Event::VOICE_SERVER_UPDATE, new Events\General\VoiceServerUpdateEvent);
            $discord->on(Event::INTERACTION_CREATE, new Events\General\InteractionCreateEvent);

            // Guild
            $discord->on(Event::GUILD_CREATE, new Events\Guild\CreateEvent);
            $discord->on(Event::GUILD_DELETE, new Events\Guild\DeleteEvent);
            $discord->on(Event::GUILD_UPDATE, new Events\Guild\UpdateEvent);

            $discord->on(Event::GUILD_BAN_ADD, new Events\Guild\Ban\AddEvent);
            $discord->on(Event::GUILD_BAN_REMOVE, new Events\Guild\Ban\RemoveEvent);

            $discord->on(Event::GUILD_MEMBER_ADD, new Events\Guild\Member\AddEvent);
            $discord->on(Event::GUILD_MEMBER_REMOVE, new Events\Guild\Member\RemoveEvent);
            $discord->on(Event::GUILD_MEMBER_UPDATE, new Events\Guild\Member\UpdateEvent);

            $discord->on(Event::GUILD_ROLE_CREATE, new Events\Guild\Role\CreateEvent);
            $discord->on(Event::GUILD_ROLE_UPDATE, new Events\Guild\Role\UpdateEvent);
            $discord->on(Event::GUILD_ROLE_DELETE, new Events\Guild\Role\DeleteEvent);

            $discord->on(Event::GUILD_INTEGRATIONS_UPDATE, new Events\Guild\IntegrationsUpdateEvent);

            $discord->on(Event::INVITE_CREATE, new Events\Guild\Invite\CreateEvent);
            $discord->on(Event::INVITE_DELETE, new Events\Guild\Invite\DeleteEvent);

            // Channel
            $discord->on(Event::CHANNEL_CREATE, new Events\Channel\CreateEvent);
            $discord->on(Event::CHANNEL_DELETE, new Events\Channel\DeleteEvent);
            $discord->on(Event::CHANNEL_UPDATE, new Events\Channel\UpdateEvent);
            $discord->on(Event::CHANNEL_PINS_UPDATE, new Events\Channel\PinsUpdateEvent);

            // Messages
            $discord->on(Event::MESSAGE_CREATE, new Events\Message\CreateEvent);
            $discord->on(Event::MESSAGE_UPDATE, new Events\Message\UpdateEvent);
            $discord->on(Event::MESSAGE_DELETE, new Events\Message\DeleteEvent);
            $discord->on(Event::MESSAGE_DELETE_BULK, new Events\Message\DeleteBulkEvent);

            $discord->on(Event::MESSAGE_REACTION_ADD, new Events\Message\Reaction\AddEvent);
            $discord->on(Event::MESSAGE_REACTION_REMOVE, new Events\Message\Reaction\RemoveEvent);
            $discord->on(Event::MESSAGE_REACTION_REMOVE_ALL, new Events\Message\Reaction\RemoveAllEvent);
            $discord->on(Event::MESSAGE_REACTION_REMOVE_EMOJI, new Events\Message\Reaction\RemoveEmojiEvent);

            // Ready

            Log::info('Connected and ready.');
            $output->info('Connected and ready.');
        });

        $output->info('Starting bot...');

        $this->getDiscord()->run();
    }
}
