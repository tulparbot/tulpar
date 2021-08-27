<?php

namespace App\Tulpar\Contracts;

use App\Models\ChannelRestrict;
use Discord\Discord;
use Discord\Parts\Channel\Message;

interface RestrictInterface
{
    /**
     * The Tulpar Bot Restrict Constructor.
     *
     * @param ChannelRestrict $channelRestrict
     * @param Message         $message
     * @param Discord         $discord
     */
    public function __construct(ChannelRestrict $channelRestrict, Message $message, Discord $discord);

    /**
     * Check user is authorized.
     *
     * @return bool
     */
    public function isAuthorized(): bool;

    /**
     * Warn the user.
     */
    public function warn(): void;

    /**
     * Execute the restrict.
     */
    public function run(): bool;
}
