<?php

namespace App\Tulpar\CommandTraits;

use Discord\Parts\User\Member;

trait HasMemberArgument
{
    /**
     * @param int|string $argument
     * @param bool       $failMessage
     * @return Member|false|null
     */
    public function getMemberArgument(int|string $argument = 0, bool $failMessage = false): Member|false|null
    {
        if (!$this->userCommand->hasArgument($argument)) {
            return null;
        }

        $id = $this->userCommand->getArgument($argument);
        $member = $this->message->channel->guild->members->get('id', $id);
        
        if (!$member instanceof Member && $failMessage) {
            $this->message->reply($this::getHelp());
            return false;
        }

        return $member;
    }
}
