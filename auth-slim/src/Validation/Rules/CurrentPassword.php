<?php

namespace AuthSlim\Validation\Rules;

use AuthSlim\Auth\Auth;
use Respect\Validation\Rules\AbstractRule;

/**
 * Do not use this if user is not authenticated.
 */
class CurrentPassword extends AbstractRule
{
    public function validate($input)
    {
        $user = Auth::user();
        return password_verify($input, $user->password);
    }
}