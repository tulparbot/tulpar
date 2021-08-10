<?php

namespace App\Tulpar\Workflow;

use App\Enums\WorkAction;
use Closure;
use Discord\Discord;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Message;
use Illuminate\Support\Str;
use React\Promise\ExtendedPromiseInterface;
use React\Promise\PromiseInterface;

class Work
{
    public string $action = WorkAction::Anything;
    public array $data = [];
    public Closure|null $then = null;

    public function run(Message $message, Discord $discord)
    {
        $execution = $this->{Str::camel($this->action)}(...func_get_args());
        if (!$execution instanceof PromiseInterface) {
            $this->then?->__invoke();
        }
        else {
            $execution->then(function () {
                $this->then?->__invoke();
            });
        }
    }

    public function anything(): void
    {
        // ..
    }

    public function sendMessage(Message $message, Discord $discord): ExtendedPromiseInterface
    {
        return $message->channel->sendMessage(
            $this->data['message'] ?? 'Please set the message in your control panel.'
        );
    }

    public function sendMessageAnotherChannel(Message $message, Discord $discord): PromiseInterface|null
    {
        $channel_id = $this->data['channel_id'] ?? null;
        $content = $this->data['message'] ?? 'Please set the message in your control panel.';

        if ($channel_id !== null) {
            return $message->channel->guild->channels->fetch($channel_id, true)->then(function (Channel $channel) use ($content) {
                $channel->sendMessage($content);
            });
        }

        return null;
    }

    public function sendPrivateMessage(Message $message, Discord $discord): ExtendedPromiseInterface
    {
        return $message->user->sendMessage(
            $this->data['message'] ?? 'Please set the message in your control panel.'
        );
    }

    public function giveRole(Message $message, Discord $discord): ExtendedPromiseInterface|null
    {
        $role_id = $this->data['role_id'] ?? null;
        if ($role_id !== null) {
            return $message->member->addRole($role_id);
        }

        return null;
    }

    public function removeRole(Message $message, Discord $discord): ExtendedPromiseInterface|null
    {
        $role_id = $this->data['role_id'] ?? null;
        if ($role_id !== null) {
            return $message->member->removeRole($role_id);
        }

        return null;
    }
}
