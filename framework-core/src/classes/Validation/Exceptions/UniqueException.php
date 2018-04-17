<?php

namespace FrameworkCore\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class UniqueException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => "{{column}} is already exist."
        ]
    ];
}