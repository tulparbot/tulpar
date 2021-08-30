<?php

namespace App\Tulpar\Filters\Chat;

use App\Tulpar\Filters\BaseFilter;
use App\Tulpar\Guard;
use Discord\Parts\Channel\Message;

class ZalgoFilter extends BaseFilter
{
    /**
     * @inheritDoc
     */
    public function check(): bool
    {
        if (Guard::isRoot($this->message->member) || Guard::isModerator($this->message->guild, $this->message->member)) {
            return false;
        }

        $text = $this->message->content;
        return preg_match('/[\xCC\xCD]/', $text);
    }

    /**
     * @inheritDoc
     */
    public function run(): Message
    {
        if ($this->check()) {
            $this->message->reply('Do not send zalgo text!');
        }

        return $this->message;
    }
}
