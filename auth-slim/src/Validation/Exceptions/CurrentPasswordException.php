<?php

namespace AuthSlim\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class CurrentPasswordException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => "Current password is invalid."
        ]
    ];
}