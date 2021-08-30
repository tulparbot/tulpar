<?php

namespace App\Tulpar\Filters\Chat;

use App\Tulpar\Filters\BaseFilter;
use App\Tulpar\Guard;
use Discord\Parts\Channel\Message;

class RepeatTextFilter extends BaseFilter
{
    /**
     * @inheritDoc
     */
    public function check(): bool
    {
        if (Guard::isRoot($this->message->member) || Guard::isModerator($this->message->guild, $this->message->member)) {
            return false;
        }

        return preg_match('/(.{10,})\\1{2,}/', mb_strtolower(trim($this->message->content)));
    }

    /**
     * @inheritDoc
     */
    public function run(): Message
    {
        if ($this->check()) {
            $this->message->reply('Do not send duplicated text!');
        }

        return $this->message;
    }
}
