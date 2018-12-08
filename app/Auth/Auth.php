<?php

namespace SkeletonAuth;

use App\Models\User;

class Auth
{
    public static function validateCredential($email, $password)
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
        if (!is_null(\Session::get('auth_user_id')))
        {
            return User::find(\Session::get('auth_user_id'));
        }

        return null;
    }

    public static function loggedInByUserId($user_id)
    {
        $logged_in_token = uniqid();

        $user = User::find($user_id);

        if (!is_null($user))
        {
            \Log::info("Login: ". $user->first_name . " " . $user->last_name);

            \Session::set('auth_user_id', $user_id);
            \Session::set('logged_in_token', $logged_in_token);
        }
        else
        {
            \Log::error("User id {$user_id} is not exist.");
        }
    }

    public static function loggedOut()
    {
        $user_id = \Session::get('auth_user_id');
        $user = User::find($user_id);

        if (!is_null($user))
        {
            \Log::info("Logout: ". $user->first_name . " " . $user->last_name);
            \Session::destroy(['auth_user_id', 'logged_in_token']);
        }
        else
        {
            \Log::error("User id {$user_id} is not exist.");
        }
    }

    public static function check()
    {
        $user_id = \Session::get('auth_user_id');
        $user = User::find($user_id);

        return !is_null($user);
    }
}
