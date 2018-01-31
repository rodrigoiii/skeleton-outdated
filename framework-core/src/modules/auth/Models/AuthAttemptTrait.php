<?php

namespace Framework\Auth\Models;

use Carbon\Carbon;
use Framework\Auth\Bridge;

trait AuthAttemptTrait
{
    public static function add($email, $uri)
    {
        return static::create([
            'email' => $email,
            'request_uri' => $uri,
            'ip_address' => get_user_ip(),
            'attempted' => Carbon::now()
        ]);
    }

    public static function getByIpAndUri()
    {
        return static::where('ip_address', get_user_ip())
                    ->where('request_uri', $_SERVER['REQUEST_URI'])
                    ->orderBy('attempted', 'DESC');
    }

    public static function reset()
    {
        $ids = static::getByIpAndUri()
                ->get()
                ->pluck('id')
                ->toArray();

        return static::destroy($ids);
    }

    public static function isValidToLogin()
    {
        $default_login_lock = Bridge::config('default_login_lock_time');

        $last_attempt = static::getByIpAndUri()
                        ->whereRaw("attempted <= DATE_SUB(NOW(), INTERVAL {$default_login_lock} SECOND)")
                        ->first();

        return !is_null($last_attempt);
    }

    public static function isAttemptOver()
    {
        $default_login_attempt_length = Bridge::config('default_login_attempt_length');

        return static::getByIpAndUri()
                ->get()
                ->count() >= $default_login_attempt_length;
    }
}