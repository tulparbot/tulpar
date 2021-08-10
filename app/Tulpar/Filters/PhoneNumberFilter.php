<?php

namespace App\Tulpar\Filters;

use App\Tulpar\Contracts\FilterInterface;
use Discord\Discord;
use Discord\Parts\Channel\Message;

class PhoneNumberFilter implements FilterInterface
{
    public bool $resend = true;

    public bool $delete = true;

    /**
     * @inheritDoc
     */
    public function __construct(public Message $message, public Discord $discord)
    {
        // ...
    }

    /**
     * @inheritDoc
     */
    public function check(): bool
    {
        $message = $this->message->content;
        preg_match('/([\+0-9]{1,14}+)/', $message, $output_array);
        return count($output_array);
    }

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        if ($this->check()) {
            if ($this->resend) {
                $this->message->channel->sendMessage('<@' . $this->message->user_id . '>: ' . preg_replace('/([\+0-9]{1,14}+)/', '(Censored by Security)', $this->message->content));
            }

            if ($this->delete) {
                $this->message->delete();
            }
        }
    }
}
