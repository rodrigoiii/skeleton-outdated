<?php

namespace App\SkeletonAuthAdmin\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class AdminEmailExistException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => "Email is not exist."
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => "Email is already exist."
        ]
    ];
}
