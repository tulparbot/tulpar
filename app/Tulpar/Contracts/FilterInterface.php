<?php

namespace App\Tulpar\Contracts;

use Discord\Discord;
use Discord\Parts\Channel\Message;

interface FilterInterface
{
    /**
     * The Tulpar Bot Message Filter Constructor.
     *
     * @param Message $message
     * @param Discord $discord
     */
    public function __construct(Message $message, Discord $discord);

    /**
     * Check the message content.
     *
     * @return bool
     */
    public function check(): bool;

    /**
     * Execute the filter.
     */
    public function run(): Message|null;
}
