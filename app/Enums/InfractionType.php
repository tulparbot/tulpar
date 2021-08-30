<?php

namespace App\Enums;

abstract class InfractionType
{
    const Custom = 'custom';
    const Mute = 'mute';
    const Kick = 'kick';
    const TempBan = 'temp-ban';
    const Ban = 'ban';
    const HardBan = 'hard-ban';
}
