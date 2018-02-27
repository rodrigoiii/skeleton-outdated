<?php

namespace Framework\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class PasswordVerifyException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => "Invalid password"
        ]
    ];
}