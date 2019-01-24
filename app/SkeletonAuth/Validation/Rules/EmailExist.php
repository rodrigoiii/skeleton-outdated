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
    public $email_exception;

    function __construct($email_exception = null)
    {
        $this->email_exception = $email_exception;
    }

    /**
     * Validate the email provided.
     *
     * @param  mixed $email
     * @return boolean
     */
    public function validate($email)
    {
        $user = User::findByEmail($email);

        if (!is_null($user))
        {
            if ($user !== $this->email_exception)
            {
                return true;
            }
        }

        return false;
    }
}
