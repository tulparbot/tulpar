<?php

namespace App\Enums;

abstract class WorkAction
{
    const Anything = 'anything';

    const SendMessage = 'send-message';
    const SendMessageAnotherChannel = 'send-message-another-channel';
    const SendPrivateMessage = 'send-private-message';

    const GiveRole = 'give-role';
    const RemoveRole = 'remove-role';
}
