<?php

namespace App\Tulpar\Filters\Chat;

use App\Tulpar\Filters\BaseFilter;
use App\Tulpar\Guard;
use Discord\Parts\Channel\Message;

class LinkFilter extends BaseFilter
{
    /**
     * @inheritDoc
     */
    public function check(): bool
    {
        if (Guard::isRoot($this->message->member) || Guard::isModerator($this->message->guild, $this->message->member)) {
            return false;
        }

        preg_match_all('/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $this->message->content, $result, PREG_PATTERN_ORDER);
        return count($result[0]);
    }

    /**
     * @inheritDoc
     */
    public function run(): Message
    {
        if ($this->check()) {
            $this->message->reply('Do not send links!');
        }

        return $this->message;
    }
}
