<?php

namespace App\Tulpar\Restricts;

use App\Enums\ChannelRestricts;
use App\Tulpar\Tulpar;

class TextRestrict extends BaseRestrict
{
    /**
     * @inheritDoc
     */
    public function run(): bool
    {
        if ($this->isAuthorized()) {
            return false;
        }

        if ($this->channelRestrict->restrict != ChannelRestricts::TextOnly) {
            return false;
        }

        if (count($this->message->attachments) > 0 || mb_strlen($this->message->content) < 1) {
            $this->warn();
            return true;
        }

        if (filter_var($this->message->content, FILTER_VALIDATE_URL)) {
            $this->warn();
            return true;
        }

        if (str_starts_with(mb_strtolower($this->message->content), Tulpar::getPrefix($this->message->guild))) {
            $this->warn();
            return true;
        }

        return false;
    }
}
