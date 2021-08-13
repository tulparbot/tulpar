<?php

namespace App\Tulpar\Filters\Chat;

use App\Tulpar\Filters\BaseFilter;
use Discord\Parts\Channel\Message;

class UppercaseFilter extends BaseFilter
{
    /**
     * @inheritDoc
     */
    public function check(): bool
    {
        if (mb_strlen($this->message->content) < 1) {
            return false;
        }

        $count = 0;
        $matches = [];
        if (preg_match_all('/[A-Z]/', $this->message->content, $matches) > 0) {
            foreach ($matches[0] as $match) {
                $count += mb_strlen($match);
            }
        }

        return (($count / mb_strlen($this->message->content)) * 100) > 70;
    }

    /**
     * @inheritDoc
     */
    public function run(): Message
    {
        if ($this->check()) {
            $this->message->reply('Do not use uppercase characters!');
        }

        return $this->message;
    }
}
