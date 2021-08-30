<?php

use App\Enums\CommandCategory;
use App\Enums\VersionType;
use App\Tulpar\Commands\Authorization\RegisterCommand;
use App\Tulpar\Commands\Authorization\RootCommand;
use App\Tulpar\Commands\Authorization\WhoamiCommand;
use App\Tulpar\Commands\Birthday\BirthdayCommand;
use App\Tulpar\Commands\Birthday\ForgetCommand;
use App\Tulpar\Commands\Birthday\NextBirthdaysCommand;
use App\Tulpar\Commands\Birthday\RememberCommand;
use App\Tulpar\Commands\Birthday\SetUserBirthdayCommand;
use App\Tulpar\Commands\Birthday\UnsetUserBirthdayCommand;
use App\Tulpar\Commands\Chat\AnnounceCommand;
use App\Tulpar\Commands\Chat\ClearChannelCommand;
use App\Tulpar\Commands\Chat\EmoticonsCommand;
use App\Tulpar\Commands\Chat\HelloCommand;
use App\Tulpar\Commands\Chat\TemporaryChannelCommand;
use App\Tulpar\Commands\Development\BotCommand;
use App\Tulpar\Commands\Development\ClearFileSystemCommand;
use App\Tulpar\Commands\Development\LogCommand;
use App\Tulpar\Commands\Development\RestartCommand;
use App\Tulpar\Commands\Development\StatusCommand;
use App\Tulpar\Commands\Development\StopCommand;
use App\Tulpar\Commands\Development\TestCommand;
use App\Tulpar\Commands\Game\ActivityCommand;
use App\Tulpar\Commands\Game\HangmanCommand;
use App\Tulpar\Commands\Game\HeadsTailsCommand;
use App\Tulpar\Commands\General\AboutCommand;
use App\Tulpar\Commands\General\AboutServerCommand;
use App\Tulpar\Commands\General\AboutUserCommand;
use App\Tulpar\Commands\General\BugCommand;
use App\Tulpar\Commands\General\GiveawayCommand;
use App\Tulpar\Commands\General\HelpCommand;
use App\Tulpar\Commands\General\InviteCommand;
use App\Tulpar\Commands\General\MoveCommand;
use App\Tulpar\Commands\General\PingCommand;
use App\Tulpar\Commands\General\VersionCommand;
use App\Tulpar\Commands\Management\PrefixCommand;
use App\Tulpar\Commands\Moderation\BanCommand;
use App\Tulpar\Commands\Moderation\KickCommand;
use App\Tulpar\Commands\Moderation\RestrictChannelCommand;
use App\Tulpar\Commands\Moderation\SlowModeCommand;
use App\Tulpar\Commands\Moderation\TempBanCommand;
use App\Tulpar\Commands\Moderation\UnbanCommand;
use App\Tulpar\Commands\Moderation\WarnCommand;
use App\Tulpar\Commands\Music\MusicCommand;
use App\Tulpar\Commands\Music\NowPlayingCommand;
use App\Tulpar\Commands\Music\PlayCommand;
use App\Tulpar\Commands\Other\ExchangeCommand;
use App\Tulpar\Commands\Other\TwitchCommand;
use App\Tulpar\Commands\Rank\RankCommand;
use App\Tulpar\Filters\Chat\RepeatFilter;
use App\Tulpar\Filters\Chat\UppercaseFilter;
use App\Tulpar\Restricts\CommandRestrict;
use App\Tulpar\Restricts\ImageRestrict;
use App\Tulpar\Restricts\LinkRestrict;
use App\Tulpar\Restricts\TextRestrict;
use App\Tulpar\Timers\ActivityTimer;
use App\Tulpar\Timers\CleanStorageTimer;
use App\Tulpar\Timers\JobTimer;
use App\Tulpar\Timers\StatisticsTimer;
use App\Tulpar\Timers\TwitchTimer;
use Discord\Parts\User\Activity;

return [
    /*
    |--------------------------------------------------------------------------
    | Set tulpar version type
    |--------------------------------------------------------------------------
    */
    'type' => env('TULPAR_TYPE', VersionType::Release),

    /*
    |--------------------------------------------------------------------------
    | Command options
    |--------------------------------------------------------------------------
    */
    'command' => [

        /*
        |--------------------------------------------------------------------------
        | Commands prefix
        |--------------------------------------------------------------------------
        */
        'prefix' => env('TULPAR_COMMAND_PREFIX', '!'),

        /*
        |--------------------------------------------------------------------------
        | Send alert message if command is not exists.
        |--------------------------------------------------------------------------
        */
        'unknown_alert' => env('TULPAR_COMMAND_UNKNOWN_ALERT', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Bot control server configuration
    |--------------------------------------------------------------------------
    */
    'server' => [

        /*
        |--------------------------------------------------------------------------
        | Server id
        |--------------------------------------------------------------------------
        */
        'id' => env('TULPAR_SERVER_ID', null),

        /*
        |--------------------------------------------------------------------------
        | Server channels
        |--------------------------------------------------------------------------
        */
        'channel' => [

            /*
            |--------------------------------------------------------------------------
            | Logging channel id
            |--------------------------------------------------------------------------
            */
            'log' => env('TULPAR_CHANNEL_LOG', null),

            /*
            |--------------------------------------------------------------------------
            | Moderation channel id
            |--------------------------------------------------------------------------
            */
            'moderation' => env('TULPAR_CHANNEL_MODERATION', null),

            /*
            |--------------------------------------------------------------------------
            | Debug channel id
            |--------------------------------------------------------------------------
            */
            'debug' => env('TULPAR_CHANNEL_DEBUG', null),
        ],

        /*
        |--------------------------------------------------------------------------
        | Channel logging features
        |--------------------------------------------------------------------------
        */
        'logging' => [
            'emergency' => env('TULPAR_SERVER_LOGGING_EMERGENCY', true),
            'alert' => env('TULPAR_SERVER_LOGGING_ALERT', false),
            'critical' => env('TULPAR_SERVER_LOGGING_CRITICAL', true),
            'error' => env('TULPAR_SERVER_LOGGING_ERROR', true),
            'warning' => env('TULPAR_SERVER_LOGGING_WARNING', false),
            'notice' => env('TULPAR_SERVER_LOGGING_NOTICE', false),
            'info' => env('TULPAR_SERVER_LOGGING_INFO', false),
            'debug' => env('TULPAR_SERVER_LOGGING_DEBUG', false),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Activated command classes
    |--------------------------------------------------------------------------
    */
    'commands' => [
        BirthdayCommand::class,
        ForgetCommand::class,
        NextBirthdaysCommand::class,
        RememberCommand::class,
        SetUserBirthdayCommand::class,
        UnsetUserBirthdayCommand::class,

        ClearFileSystemCommand::class,
        TestCommand::class,
        WhoamiCommand::class,
        StopCommand::class,
        RestartCommand::class,
        RootCommand::class,
        LogCommand::class,
        BotCommand::class,

        RegisterCommand::class,
        HelloCommand::class,
        AboutCommand::class,
        VersionCommand::class,
        EmoticonsCommand::class,
        InviteCommand::class,
        ClearChannelCommand::class,
        HelpCommand::class,
        PingCommand::class,
        AboutUserCommand::class,
        AboutServerCommand::class,
        BugCommand::class,

        MusicCommand::class,

        WarnCommand::class,
        RestrictChannelCommand::class,
        SlowModeCommand::class,
        TempBanCommand::class,
        BanCommand::class,
        UnbanCommand::class,
        KickCommand::class,

        HangmanCommand::class,

        RankCommand::class,
        PrefixCommand::class,
        GiveawayCommand::class,
        AnnounceCommand::class,

        HeadsTailsCommand::class,
        ActivityCommand::class,
        MoveCommand::class,
        TwitchCommand::class,
        StatusCommand::class,
        ExchangeCommand::class,
        TemporaryChannelCommand::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Command categories
    |--------------------------------------------------------------------------
    */
    'categories' => [
        CommandCategory::Authorization => (object)[
            'name' => 'Authorization',
            'emoticon' => '🔐',
            'guard' => '*',
            'commands' => [
                RegisterCommand::class,
                RootCommand::class,
                WhoamiCommand::class,
            ],
        ],
        CommandCategory::Birthdays => (object)[
            'name' => 'Birthdays',
            'emoticon' => '🎂',
            'guard' => '*',
            'commands' => [
                BirthdayCommand::class,
                ForgetCommand::class,
                NextBirthdaysCommand::class,
                RememberCommand::class,
                SetUserBirthdayCommand::class,
                UnsetUserBirthdayCommand::class,
            ],
        ],
        CommandCategory::Chat => (object)[
            'name' => 'Chat',
            'emoticon' => '✉️',
            'guard' => '*',
            'commands' => [
                AnnounceCommand::class,
                ClearChannelCommand::class,
                EmoticonsCommand::class,
                HelloCommand::class,
                TemporaryChannelCommand::class,
            ],
        ],
        CommandCategory::Development => (object)[
            'name' => 'Development',
            'emoticon' => '🧑‍💻',
            'guard' => 'root',
            'commands' => [
                BotCommand::class,
                ClearFileSystemCommand::class,
                LogCommand::class,
                RestartCommand::class,
                StatusCommand::class,
                StopCommand::class,
                TestCommand::class,
            ],
        ],
        CommandCategory::Game => (object)[
            'name' => 'Game',
            'emoticon' => '🎮',
            'guard' => '*',
            'commands' => [
                ActivityCommand::class,
                HangmanCommand::class,
                HeadsTailsCommand::class,
            ],
        ],
        CommandCategory::General => (object)[
            'name' => 'General',
            'emoticon' => '🌍',
            'guard' => '*',
            'commands' => [
                AboutCommand::class,
                AboutServerCommand::class,
                AboutUserCommand::class,
                BugCommand::class,
                GiveawayCommand::class,
                HelpCommand::class,
                InviteCommand::class,
                MoveCommand::class,
                PingCommand::class,
                VersionCommand::class,
            ],
        ],
        CommandCategory::Management => (object)[
            'name' => 'Management',
            'emoticon' => '🧑‍💼',
            'guard' => 'moderator',
            'commands' => [
                PrefixCommand::class,
            ],
        ],
        CommandCategory::Moderation => (object)[
            'name' => 'Moderation',
            'emoticon' => '👮',
            'guard' => 'moderator',
            'commands' => [
                BanCommand::class,
                KickCommand::class,
                RestrictChannelCommand::class,
                SlowModeCommand::class,
                TempBanCommand::class,
                UnbanCommand::class,
                WarnCommand::class,
            ],
        ],
        CommandCategory::Music => (object)[
            'name' => 'Music',
            'emoticon' => '🎧',
            'guard' => '*',
            'commands' => [
                MusicCommand::class,
                NowPlayingCommand::class,
                PlayCommand::class,
            ],
        ],
        CommandCategory::Rank => (object)[
            'name' => 'Rank',
            'emoticon' => '🆙',
            'guard' => '*',
            'commands' => [
                RankCommand::class,
            ],
        ],
        CommandCategory::Other => (object)[
            'name' => 'Other',
            'emoticon' => '🌐',
            'guard' => '*',
            'commands' => [
                ExchangeCommand::class,
                TwitchCommand::class,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Restrict classes
    |--------------------------------------------------------------------------
    */
    'restricts' => [
        CommandRestrict::class,
        LinkRestrict::class,
        ImageRestrict::class,
        TextRestrict::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Command Aliases
    |--------------------------------------------------------------------------
    */
    'aliases' => [
        HelloCommand::class => ['merhaba', 'mrb', 'hi'],
        HeadsTailsCommand::class => ['yazitura'],
        HangmanCommand::class => ['adamasmaca'],
        GiveawayCommand::class => ['cekilis'],
        BugCommand::class => ['hatabildir'],
        MusicCommand::class => ['müzik', 'şarkı'],
        HelpCommand::class => ['yardım', 'yardim'],
        InviteCommand::class => ['davet', 'botdavet'],
        RegisterCommand::class => ['kayıt', 'kayit'],
        AboutServerCommand::class => ['serverinfo', 'sunucubilgi'],
        AboutUserCommand::class => ['userinfo', 'kullanicibilgi', 'kullanıcıbilgi'],
        EmoticonsCommand::class => ['emojiler', 'sunucuemojileri', 'emotes'],
        AnnounceCommand::class => ['botduyuru'],
        RestartCommand::class => ['yenidenbaşlat', 'yenidenbaslat', 'botrr', 'rr'],
        RootCommand::class => ['admin', 'administrator', 'yonetici', 'yönetici'],
        BotCommand::class => ['botdurum', 'botaktivite'],
        AboutCommand::class => ['bothakkinda', 'botbilgisi', 'botbilgi'],
        WhoamiCommand::class => ['yetkim'],
        StopCommand::class => ['botudurdur', 'botst', 'botstop'],
        ClearChannelCommand::class => ['sil', 'temizle', 'yoket'],
        ActivityCommand::class => ['aktivite'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Activity List
    |--------------------------------------------------------------------------
    */
    'activities' => [
        (object)[
            'name' => 'Tulpar Bot top.gg',
            'type' => Activity::TYPE_COMPETING,
        ],
        (object)[
            'name' => '{prefix}yardım',
            'type' => Activity::TYPE_PLAYING,
        ],
        (object)[
            'name' => 'Toplam {guild_count} Sunucuda!',
            'type' => Activity::TYPE_LISTENING,
        ],
        (object)[
            'name' => 'Toplam {member_count} Kullanıcı!',
            'type' => Activity::TYPE_STREAMING,
        ],
        (object)[
            'name' => 'Toplam {command_count} Komut!',
            'type' => Activity::TYPE_WATCHING,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Timers (Intervals are seconds)
    |--------------------------------------------------------------------------
    */
    'timers' => [
        3 => [
            JobTimer::class,
        ],
        5 => [
            ActivityTimer::class,
        ],
        5 * 60 => [
            StatisticsTimer::class,
        ],
        15 * 60 => [
            TwitchTimer::class,
        ],
        60 * 60 * 24 => [
            CleanStorageTimer::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Activated filter classes
    |--------------------------------------------------------------------------
    */
    'filters' => [
        UppercaseFilter::class,
        RepeatFilter::class,
    ],

    'requires' => [
        'guild' => [
            'permissions' => (array)[
                'connect',
                'speak',
                'mute_members',
                'deafen_members',
                'move_members',

                'add_reactions',
                'send_messages',
                'manage_messages',
                'embed_links',
                'attach_files',
                'read_message_history',
                'mention_everyone',
                'use_external_emojis',

                'kick_members',
                'ban_members',
                'change_nickname',
                'manage_nicknames',
                'manage_emojis',

                'create_instant_invite',
                'manage_channels',
                'view_channel',
            ],
        ],
    ],
];
