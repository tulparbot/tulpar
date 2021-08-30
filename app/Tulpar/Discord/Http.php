<?php

namespace App\Tulpar\Discord;

class Http extends \Discord\Http\Http
{
    /**
     * Returns the User-Agent of the HTTP client.
     *
     * @return string
     */
    public function getUserAgent(): string
    {
        return 'Tulpar Bot (https://isaeken.com.tr)';
    }
}
