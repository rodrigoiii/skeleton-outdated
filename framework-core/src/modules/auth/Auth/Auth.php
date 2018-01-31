<?php

namespace Framework\Auth\Auth;

use Carbon\Carbon;
use Framework\Auth\Bridge;
use Framework\Utilities\Session;

class Auth
{
    /**
     * Attempt to log in via email and password
     * @param  string $email    - email address
     * @param  string $password - password
     * @return boolean
     */
    public static function attempt($email, $password)
    {
        $User = Bridge::model("User");

        $user = $User::getByEmailAndPassword($email, $password);

        if (is_null($user) )
            return false;

        $token = sha1(uniqid());

        Session::put('auth_id', $user->id);
        Session::put('auth_token', $token);
        Session::put('auth_session_start', Carbon::now()->toDateTimeString());

        $user->setAuthToken($token);

        return true;
    }

    /**
     * Check if admin is logged in
     * @return boolean
     */
    public static function check()
    {
        $User = Bridge::model("User");

        if (!Session::_isSet('auth_id') || !Session::_isSet('auth_token') || !Session::_isSet('auth_session_start'))
            return false;

        $admin = $User::find(Session::get('auth_id'));

        if (!$admin)
            return false;

        return $admin->auth_token === Session::get('auth_token');
    }

    /**
     * Get the logged in user
     * @return object - user object
     */
    public static function user()
    {
        $User = Bridge::model("User");
        return static::check() ? $User::find(Session::get('auth_id')) : null;
    }

    /**
     * Flush all session related in authentication
     * @return [type] [description]
     */
    public static function logout()
    {
        Session::destroy(['auth_id', 'auth_token', 'auth_session_start']);
    }

    /**
     * Check if the session time is already expired
     * @return boolean [description]
     */
    public static function isExpired()
    {
        if (static::check())
        {
            return Carbon::parse(Session::get('auth_session_start'))
                    ->addSeconds(Bridge::config("default_session_expiration")) < Carbon::now();
        }

        return true;
    }
}