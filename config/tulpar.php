<?php

use App\Tulpar\Commands\Authorization\RegisterCommand;
use App\Tulpar\Commands\Authorization\RootCommand;
use App\Tulpar\Commands\Authorization\WhoamiCommand;
use App\Tulpar\Commands\Chat\AnnounceCommand;
use App\Tulpar\Commands\Chat\ClearChannelCommand;
use App\Tulpar\Commands\Chat\EmoticonsCommand;
use App\Tulpar\Commands\Chat\HelloCommand;
use App\Tulpar\Commands\Development\BotCommand;
use App\Tulpar\Commands\Development\LogCommand;
use App\Tulpar\Commands\Development\RestartCommand;
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
use App\Tulpar\Commands\General\PingCommand;
use App\Tulpar\Commands\General\VersionCommand;
use App\Tulpar\Commands\Management\PrefixCommand;
use App\Tulpar\Commands\Moderation\BanCommand;
use App\Tulpar\Commands\Moderation\KickCommand;
use App\Tulpar\Commands\Moderation\UnbanCommand;
use App\Tulpar\Commands\Music\MusicCommand;
use App\Tulpar\Commands\Rank\RankCommand;
use App\Tulpar\Filters\Chat\RepeatFilter;
use App\Tulpar\Filters\Chat\UppercaseFilter;

return [
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
        (object) [
            'name' => 'Tulpar Bot top.gg',
            'type' => \Discord\Parts\User\Activity::TYPE_COMPETING,
        ],
        (object) [
            'name' => '{prefix}yardım',
            'type' => \Discord\Parts\User\Activity::TYPE_PLAYING,
        ],
        (object) [
            'name' => 'Toplam {guild_count} Sunucuda!',
            'type' => \Discord\Parts\User\Activity::TYPE_LISTENING,
        ],
        (object) [
            'name' => 'Toplam {member_count} Kullanıcı!',
            'type' => \Discord\Parts\User\Activity::TYPE_STREAMING,
        ],
        (object) [
            'name' => 'Toplam {command_count} Komut!',
            'type' => \Discord\Parts\User\Activity::TYPE_WATCHING,
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
