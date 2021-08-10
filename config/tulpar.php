<?php

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
];
