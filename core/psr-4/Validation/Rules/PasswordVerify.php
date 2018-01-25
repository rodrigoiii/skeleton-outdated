<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class PasswordVerify extends AbstractRule
{
    public $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function validate($input)
    {
        return password_verify(sha1($input), $this->password);
    }
}