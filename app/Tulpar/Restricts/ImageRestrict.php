<?php

namespace App\Tulpar\Restricts;

use App\Enums\ChannelRestricts;
use Exception;

class ImageRestrict extends BaseRestrict
{
    /**
     * @var string[] $mimes
     */
    public static array $mimes = [
        'image/png',
        'image/jpg',
        'image/jpeg',
        'image/gif',
        'image/webp',
        'image/webm',
        'image/mp4',
    ];

    /**
     * @inheritDoc
     */
    public function run(): bool
    {
        if ($this->isAuthorized()) {
            return false;
        }
        
        if ($this->channelRestrict->restrict != ChannelRestricts::ImageOnly) {
            return false;
        }

        if (mb_strlen($this->message->content) > 0 || count($this->message->attachments) < 1) {
            if (filter_var($this->message->content, FILTER_VALIDATE_URL)) {
                try {
                    $size = getimagesize($this->message->content);
                    if (isset($size['mime']) && in_array($size['mime'], static::$mimes)) {
                        return false;
                    }
                } catch (Exception $exception) {
                    // ...
                }
            }

            $this->message->delete();
            $this->warn();
            return true;
        }

        foreach ($this->message->attachments as $attachment) {
            if (!(isset($attachment->content_type) && in_array($attachment->content_type, static::$mimes))) {
                $this->message->delete();
                $this->warn();
                return true;
            }
        }

        return false;
    }
}
