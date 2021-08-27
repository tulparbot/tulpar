<?php

namespace App\Tulpar\Restricts;

use App\Models\ChannelRestrict;
use App\Support\Str;
use App\Tulpar\Contracts\RestrictInterface;
use App\Tulpar\Guard;
use App\Tulpar\Helpers;
use Discord\Discord;
use Discord\Parts\Channel\Message;

abstract class BaseRestrict implements RestrictInterface
{
    /**
     * @inheritDoc
     */
    public function __construct(public ChannelRestrict $channelRestrict, public Message $message, public Discord $discord)
    {
        // ...
    }

    /**
     * @inheritDoc
     */
    public function isAuthorized(): bool
    {
        if (Guard::isRoot($this->message->member)) {
            return true;
        }

        $permissions = $this->message->member->getPermissions();
        return $permissions->administrator || $permissions->manage_channels;
    }

    /**
     * @inheritDoc
     */
    public function warn(): void
    {
        if (mb_strlen($this->channelRestrict->message) > 0) {
            $this->message->channel->sendMessage(
                Str::of($this->channelRestrict->message)
                    ->replace('%s', Helpers::userTag($this->message->member->id))
            );
        }
    }
}
