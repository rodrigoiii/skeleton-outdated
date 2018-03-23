<?php

namespace AuthSlim\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class ConfirmPasswordException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => "Password not match."
        ]
    ];
}