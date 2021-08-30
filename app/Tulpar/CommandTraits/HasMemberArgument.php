<?php

namespace App\Tulpar\CommandTraits;

use Discord\Exceptions\IntentException;
use Discord\Parts\User\Member;

trait HasMemberArgument
{
    /**
     * @param int|string $argument
     * @param bool       $failMessage
     * @return Member|null
     * @throws IntentException
     */
    public function getMemberArgument(int|string $argument = 0, bool $failMessage = false): Member|null
    {
        $member = null;
        if ($this->userCommand->hasArgument($argument)) {
            $id = $this->userCommand->getArgument($argument);
            $member = $this->message->channel->guild->members->get('id', $id);
        }

        if ($member === null && $failMessage) {
            $this->message->reply($this->translate('You can only use in members!'));
        }

        return $member;
    }
}
