<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class ConfirmPassword extends AbstractRule
{
    public $confirm_password_name;

    public function __construct($confirm_password_name = "confirm_password")
    {
        $this->confirm_password_name = $confirm_password_name;
    }

    public function validate($input)
    {
        if (isset($_POST[$this->confirm_password_name]))
            return $_POST[$this->confirm_password_name] === $input;

        return false;
    }
}