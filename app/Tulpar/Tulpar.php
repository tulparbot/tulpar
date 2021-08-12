<?php


namespace App\Tulpar;


use App\Tulpar\Events;
use Discord\Discord;
use Discord\Exceptions\IntentException;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Guild\Guild;
use Discord\Parts\Permissions\RolePermission;
use Discord\Parts\User\Member;
use Discord\WebSockets\Event;
use Exception;
use Illuminate\Console\OutputStyle;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class Tulpar
{
    /**
     * @var Tulpar|null $instance
     */
    private static Tulpar|null $instance = null;

    /**
     * @var object|null $composer
     */
    private static object|null $composer = null;

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
     * @return string
     */
    public static function getPrefix(): string
    {
        return config('tulpar.command.prefix', '!');
    }

    /**
     * @return object
     * @throws FileNotFoundException
     */
    public static function getComposer(): object
    {
        if (static::$composer === null) {
            if (!file_exists(base_path('composer.json'))) {
                throw new FileNotFoundException;
            }

            static::$composer = json_decode(file_get_contents(base_path('composer.json')));
        }

        return static::$composer;
    }

    /**
     * @return string
     */
    public static function getVersion(): string
    {
        $version = null;
        try {
            $version = static::getComposer()?->version;
        } catch (Exception $exception) {
            // ...
        }

        return $version ?? 'unreleased';
    }

    /**
     * @var Discord|null $discord
     */
    private Discord|null $discord = null;

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
     * @return Channel|null
     * @throws IntentException
     */
    public function getLogChannel(): Channel|null
    {
        return $this->getDiscord()->getChannel(config('tulpar.server.channel.log')) ?? null;
    }

    /**
     * @param Guild|string $guild
     * @param bool         $fresh
     * @return RolePermission|null
     * @throws IntentException
     */
    public function getPermissions(Guild|string $guild, bool $fresh = false): RolePermission|null
    {
        if (!$guild instanceof Guild) {
            $guild = $this->getDiscord()->guilds->get('id', $guild);
        }

        if ($guild === null) {
            return null;
        }

        $call = function () use ($guild) {
            /** @var Member $member */
            $member = $guild->members->get('id', $this->getDiscord()->id);

            if ($member === null) {
                return null;
            }

            return $member->getPermissions();
        };

        if ($fresh === true) {
            return $call();
        }

        return Cache::remember($guild->id . '-permissions', Carbon::make('+1 hours'), $call);
    }

    /**
     * @param Guild|string $guild
     * @param string       $permission
     * @param bool         $fresh
     * @return bool
     * @throws IntentException
     */
    public function checkPermission(Guild|string $guild, string $permission, bool $fresh = false): bool
    {
        return $this->getPermissions($guild, $fresh)?->{$permission} === true;
    }

    /**
     * @param Guild|string $guild
     * @param bool         $fresh
     * @return bool
     * @throws IntentException
     */
    public function checkPermissions(Guild|string $guild, bool $fresh = false): bool
    {
        $permissions = config('tulpar.guild.permissions', []);

        foreach ($permissions as $permission) {
            if (!$this->checkPermission($guild, $permission, $fresh)) {
                return false;
            }
        }

        return true;
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

    /**
     * @throws IntentException
     */
    public function stop(): void
    {
        $this->getDiscord()->close(true);
    }
}
