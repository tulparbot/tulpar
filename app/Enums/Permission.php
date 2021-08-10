<?php

namespace App\Enums;

abstract class Permission
{
    const Unknown = 'unknown';
    const Member = 'member';
    const Administrator = 'administrator';
    const Root = 'root';
}
