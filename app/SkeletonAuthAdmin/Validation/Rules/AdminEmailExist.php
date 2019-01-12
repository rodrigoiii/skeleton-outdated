<?php

namespace App\SkeletonAuthAdmin\Validation\Rules;

use App\SkeletonAuthAdmin\Models\Admin;
use Respect\Validation\Rules\AbstractRule;

class AdminEmailExist extends AbstractRule
{
    /**
     * Validate the email provided.
     *
     * @param  mixed $email
     * @return boolean
     */
    public function validate($email)
    {
        $admin = Admin::findByEmail($email);
        $is_admin_exist = !is_null($admin);

        return $is_admin_exist;
    }
}
