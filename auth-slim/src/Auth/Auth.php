<?php

namespace AuthSlim\Auth;

use AuthSlim\Models\User;
use AuthSlim\Utilities\Session;
use Carbon\Carbon;

class Auth
{
    public static $LOGIN_SESSION_EXPIRATION = 60 * 30;
    public static $LOGIN_ATTEMPT_LENGTH = 5;
    public static $LOGIN_LOCK_TIME = 60 * 30;

    /**
     * Attempt to log in via email and password
     * @param  string $email    - email address
     * @param  string $password - password
     * @return boolean
     */
    public static function attempt($email, $password)
    {
        $user = User::getByEmailAndPassword($email, $password);

        if (is_null($user))
            return false;

        $token = uniqid();

        Session::put('auth_id', $user->id);
        Session::put('auth_token', $token);
        Session::put('auth_session_start', Carbon::now()->toDateTimeString());

        $user->setAuthToken($token);

        return true;
    }

    /**
     * Check if user is logged in
     * @return boolean
     */
    public static function check()
    {
        if (!Session::_isSet('auth_id') || !Session::_isSet('auth_token') || !Session::_isSet('auth_session_start'))
            return false;

        $user = User::find(Session::get('auth_id'));

        if (!$user)
            return false;

        return $user->auth_token === Session::get('auth_token');
    }

    /**
     * Get the logged in user
     * @return object - user object
     */
    public static function user()
    {
        return static::check() ? User::find(Session::get('auth_id')) : null;
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
                    ->addSeconds(static::$LOGIN_SESSION_EXPIRATION) <= Carbon::now();
        }

        return true;
    }
}