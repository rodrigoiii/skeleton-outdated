<?php

namespace Framework\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class ConfirmPassword extends AbstractRule
{
    public $confirm_password;

    public function __construct($confirm_password)
    {
        $this->confirm_password = $confirm_password;
    }

    public function validate($input)
    {
        return $input === $this->confirm_password;
    }
}