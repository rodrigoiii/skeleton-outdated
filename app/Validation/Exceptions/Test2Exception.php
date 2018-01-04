<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class Test2Exception extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => ""
        ]
    ];
}