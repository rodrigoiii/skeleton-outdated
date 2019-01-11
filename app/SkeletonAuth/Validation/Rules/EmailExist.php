<?php

namespace App\SkeletonAuth\Validation\Rules;

use App\SkeletonAuth\Models\User;
use Respect\Validation\Rules\AbstractRule;

class EmailExist extends AbstractRule
{
    /**
     * Validate the email provided.
     *
     * @param  mixed $email
     * @return boolean
     */
    public function validate($email)
    {
        $user = User::findByEmail($email);
        $is_user_exist = !is_null($user);

        return $is_user_exist;
    }
}
