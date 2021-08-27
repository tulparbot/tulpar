<?php

namespace App\Tulpar\Restricts;

use App\Enums\ChannelRestricts;

class CommandRestrict extends BaseRestrict
{
    /**
     * @inheritDoc
     */
    public function run(): bool
    {
        if ($this->isAuthorized()) {
            return false;
        }

        if ($this->channelRestrict->restrict != ChannelRestricts::CommandOnly) {
            return false;
        }

        $prefixes = $this->channelRestrict->getCommandPrefixesAttribute();
        $isCommand = false;

        foreach ($prefixes as $prefix) {
            if (str_starts_with($this->message->content, $prefix)) {
                $isCommand = true;
            }
        }

        if (!$isCommand) {
            $this->warn();
            return true;
        }

        return false;
    }
}
