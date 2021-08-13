<?php

namespace App\Tulpar\Filters\Chat;

use App\Tulpar\Filters\BaseFilter;
use App\Tulpar\Helpers;
use Discord\Parts\Channel\Message;

class RepeatFilter extends BaseFilter
{
    public static array $lastMessages = [];

    /**
     * @inheritDoc
     */
    public function check(): bool
    {
        if (!array_key_exists($this->message->guild_id, static::$lastMessages)) {
            static::$lastMessages[$this->message->guild_id] = [];
        }

        if (!array_key_exists($this->message->user_id, static::$lastMessages[$this->message->guild_id])) {
            static::$lastMessages[$this->message->guild_id][$this->message->user_id] = [];
        }

        static::$lastMessages[$this->message->guild_id][$this->message->user_id][time()] = $this->message->content;

        $isRepeated = false;
        $messages = collect(static::$lastMessages[$this->message->guild_id][$this->message->user_id])->reverse()->take(2);
        $before = null;

        foreach ($messages as $message) {
            if ($before == null) {
                $before = $message;
                continue;
            }

            $isRepeated = $message == $before;
        }

        return $isRepeated;
    }

    /**
     * @inheritDoc
     */
    public function run(): Message|null
    {
        if ($this->check()) {
            $this->message->channel->sendMessage('Do not send repeated messages ' . Helpers::userTag($this->message->user_id) . '!')->done(function () {
                $this->message->delete();
            });
            return null;
        }

        return $this->message;
    }
}
