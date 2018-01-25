<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class PasswordStrength extends AbstractRule
{
    public $min_value;
    public $lower;
    public $upper;
    public $number;
    public $special_char;

    public function __construct($min_value = 8, $lower = 0, $upper = 0, $number = 0, $special_char = 0)
    {
        $this->min_value = $min_value;
        $this->lower = $lower;
        $this->upper = $upper;
        $this->number = $number;
        $this->special_char = $special_char;
    }

    public function validate($input)
    {
        return strlen($input) >= $this->min_value && // password length
        preg_match_all("/[a-z]/", $input) >= $this->lower && // lower case
        preg_match_all("/[A-Z]/", $input) >= $this->upper && // upper case
        preg_match_all("/[0-9]/", $input) >= $this->number && // upper case
        $this->filterSpecialCharacters($input) >= $this->special_char; // special characters
    }

    private function filterSpecialCharacters($input)
    {
        preg_match_all("/\W/", $input, $matches);
        $result = array_filter($matches[0], function ($char) {
            return $char !== " ";
        });

        return count($result);
    }
}