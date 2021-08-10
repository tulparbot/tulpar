<?php

namespace App\Enums;

abstract class CommandValidation
{
    const Success = 'success';
    const Unknown = 'unknown';
    const NoAccess = 'no-access';
    const InvalidArguments = 'invalid-arguments';
    const Error = 'error';
    const NotCommand = 'not-command';
    const CustomCommand = 'custom-command';
}
