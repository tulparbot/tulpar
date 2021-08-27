<?php

namespace App\Enums;

abstract class ChannelRestricts
{
    const ImageOnly = 'image';
    const TextOnly = 'text';
    const LinkOnly = 'link';
    const CommandOnly = 'command';
}
