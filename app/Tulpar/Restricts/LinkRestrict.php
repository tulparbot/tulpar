<?php

namespace App\Tulpar\Restricts;

use App\Enums\ChannelRestricts;

class LinkRestrict extends BaseRestrict
{
    /**
     * @inheritDoc
     */
    public function run(): bool
    {
        if ($this->isAuthorized()) {
            return false;
        }

        if ($this->channelRestrict->restrict != ChannelRestricts::LinkOnly) {
            return false;
        }

        if (!filter_var($this->message->content, FILTER_VALIDATE_URL)) {
            $this->warn();
            return true;
        }

        return false;
    }
}
