<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Your Twitch api key
    |--------------------------------------------------------------------------
    */
    'api_key' => env('TWITCH_API_KEY', null),

    'client' => [
        'id' => env('TWITCH_CLIENT_ID', null),
        'secret' => env('TWITCH_CLIENT_SECRET', null),
    ],
];
