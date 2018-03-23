<?php

namespace AuthSlim\Validation\Rules;

use AuthSlim\Models\User;
use Respect\Validation\Rules\AbstractRule;

class EmailExist extends AbstractRule
{
    public function validate($input)
    {
        return !is_null(User::findByEmail($input));
    }
}