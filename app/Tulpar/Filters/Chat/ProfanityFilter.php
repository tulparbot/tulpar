<?php

namespace App\Tulpar\Filters\Chat;

use App\Tulpar\Filters\BaseFilter;
use App\Tulpar\Guard;
use ConsoleTVs\Profanity\Facades\Profanity;
use Discord\Parts\Channel\Message;

class ProfanityFilter extends BaseFilter
{
    /**
     * @inheritDoc
     */
    public function check(): bool
    {
        if (Guard::isRoot($this->message->member) || Guard::isModerator($this->message->guild, $this->message->member)) {
            return false;
        }

        return count(Profanity::blocker($this->message->content)->badWords()) > 0;
    }

    /**
     * @inheritDoc
     */
    public function run(): Message
    {
        if ($this->check()) {
            $this->message->reply('Do not use bad words!');
        }

        return $this->message;
    }
}
