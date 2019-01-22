<?php

namespace App\SkeletonAuth\Validation\Rules;

use App\SkeletonAuth\Models\User;
use Respect\Validation\Rules\AbstractRule;

class EmailExist extends AbstractRule
{
    /**
     * Email to be exclude before perform the emailExist rule.
     *
     * @var string
     */
    public $except;

    function __construct($except = null)
    {
        $this->except = $except;
    }

    /**
     * Validate the email provided.
     *
     * @param  mixed $email
     * @return boolean
     */
    public function validate($email)
    {
        $emails = User::all()->pluck('email')->toArray();
        if (!is_null($this->except))
        {
            if (in_array($this->except, $emails))
            {
                $index = array_search($this->except, $emails);
                unset($emails[$index]);
            }
        }

        return in_array($email, $emails);
    }
}
