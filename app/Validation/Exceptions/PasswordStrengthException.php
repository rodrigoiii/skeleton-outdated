<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class PasswordStrengthException extends ValidationException
{
    const MIN_LENGTH = 0;
    const LOWER = 1;
    const UPPER = 2;
    const NUMBER = 3;
    const SPECIAL_CHAR = 4;

    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::MIN_LENGTH => "Password must be at least {{min_length}} character(s).",
            self::LOWER => "Password must have {{lower}} lower case.",
            self::UPPER => "Password must have {{upper}} upper case.",
            self::NUMBER => "Password must have {{number}} number(s).",
            self::SPECIAL_CHAR => "Password must have {{special_char}} special character(s).",
        ]
    ];

    public function chooseTemplate()
    {
        $input = $this->getParam('input');

        if (strlen($input) < $this->getParam('min_length'))
            return self::MIN_LENGTH;
        if (preg_match_all("/[a-z]/", $input) < $this->getParam('lower'))
            return self::LOWER;
        if (preg_match_all("/[A-Z]/", $input) < $this->getParam('upper'))
            return self::UPPER;
        if (preg_match_all("/[0-9]/", $input) < $this->getParam('number'))
            return self::NUMBER;
        if ($this->filterSpecialCharacters($input) < $this->getParam('special_char'))
            return self::SPECIAL_CHAR;
    }

    private function filterSpecialCharacters($input)
    {
        preg_match_all("/\W/", $input, $matches);
        $result = array_filter($matches[0], function ($char) {
            return $char != " ";
        });

        return count($result);
    }
}
