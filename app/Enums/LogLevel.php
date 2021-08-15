<?php

namespace App\Enums;

abstract class LogLevel
{
    const Emergency = 'emergency';
    const Alert = 'alert';
    const Critical = 'critical';
    const Error = 'error';
    const Warning = 'warning';
    const Notice = 'notice';
    const Info = 'info';
    const Debug = 'debug';
}
