<?php

namespace App\SkeletonAuthAdmin\Validation\Rules;

use App\SkeletonAuthAdmin\Models\Admin;
use Respect\Validation\Rules\AbstractRule;

class AdminEmailExist extends AbstractRule
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
        $admin = Admin::findByEmail($email);

        if (!is_null($admin))
        {
            if ($admin !== $this->email_exception)
            {
                return true;
            }
        }

        return false;
    }
}
