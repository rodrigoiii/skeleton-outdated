<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class Test2 extends AbstractRule
{
    public function validate($input)
    {
        if (!empty($input))
        {
            // logic here...
        }

        return false;
    }
}