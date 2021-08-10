<?php

namespace App\Enums;

abstract class FilterValidation
{
    const Secure = 'success';
    const Warning = 'warning';
    const Unsafe = 'unsafe';
    const Risky = 'risky';
    const Spam = 'spam';
    const Profanity = 'profanity';
    const Advertising = 'advertising';
    const Link = 'link';
    const IpAddress = 'ip-address';
    const Email = 'email';
    const Phone = 'phone';
    const Unknown = 'unknown';
}
