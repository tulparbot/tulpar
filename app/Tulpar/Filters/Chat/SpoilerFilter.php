<?php

namespace App\Tulpar\Filters\Chat;

use App\Tulpar\Filters\BaseFilter;
use App\Tulpar\Guard;
use Discord\Parts\Channel\Message;

class SpoilerFilter extends BaseFilter
{
    /**
     * @inheritDoc
     */
    public function check(): bool
    {
        if (Guard::isRoot($this->message->member) || Guard::isModerator($this->message->guild, $this->message->member)) {
            return false;
        }

        return substr_count($this->message->content, '|') > 8;
    }

    /**
     * @inheritDoc
     */
    public function run(): Message
    {
        if ($this->check()) {
            $this->message->reply('Too many spoilers!');
        }

        return $this->message;
    }
}
