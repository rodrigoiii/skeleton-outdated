<?php

namespace SkeletonAuth;

use App\Models\User;

class Auth
{
    public static function check($email, $password)
    {
        $user = User::findByEmail($email);

        try {
            if (is_null($user)) throw new \Exception("{$email} is not exist.", 1);
            if (!password_verify($password, $user->password)) throw new \Exception("{$email} is valid but password is incorrect.", 1);

            return true;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    public static function user()
    {
        return User::find(1); // assume user id 1 is authenticated


        if (!is_null(\Session::get('auth_user_id')))
        {
            return User::find(\Session::get('auth_user_id'));
        }

        return null;
    }
}
