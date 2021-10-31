<?php

namespace App\Tulpar\Filters;

use App\Tulpar\Contracts\FilterInterface;
use Discord\Discord;
use Discord\Parts\Channel\Message;

abstract class BaseFilter implements FilterInterface
{
    /**
     * @inheritDoc
     */
    public function __construct(public Message $message, public Discord $discord)
    {
        // ...
    }
    public function translate(string $translation, array $replacements = []): string
    {
        return _text($this->message->guild, $translation, $replacements);
    }
}
