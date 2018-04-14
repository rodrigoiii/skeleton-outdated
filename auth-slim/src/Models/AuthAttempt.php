<?php

namespace AuthSlim\Models;

use AuthSlim\Auth\Auth;
use AuthSlim\Utilities\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AuthAttempt extends Model
{
    protected $fillable = ['email', 'request_uri', 'ip_address'];

    public static $ENABLE_LOGIN_LOCK = true;
    public static $LOGIN_ATTEMPT_LENGTH = 5;
    public static $LOGIN_LOCK_TIME = 60 * 30;

    public static function add($email)
    {
        if (!static::$ENABLE_LOGIN_LOCK) return;

        $parse_url = parse_url($_SERVER['REQUEST_URI']);
        $request_uri = $parse_url['path'];

        return static::create([
            'email' => $email,
            'request_uri' => $request_uri,
            'ip_address' => Helper::getUserIp()
        ]);
    }

    public static function getByIpAndUri()
    {
        $parse_url = parse_url($_SERVER['REQUEST_URI']);
        $request_uri = $parse_url['path'];

        return static::where('ip_address', Helper::getUserIp())
                    ->where('request_uri', $request_uri)
                    ->orderBy('created_at', 'DESC');
    }

    public static function reset()
    {
        if (!static::$ENABLE_LOGIN_LOCK) return;

        $ids = static::getByIpAndUri()
                ->get()
                ->pluck('id')
                ->toArray();

        return static::destroy($ids);
    }

    public static function isValidToLogin()
    {
        if (!static::$ENABLE_LOGIN_LOCK) return true;

        $default_login_lock = static::$LOGIN_LOCK_TIME;

        $last_attempt = static::getByIpAndUri()
                        ->whereRaw("created_at <= DATE_SUB(NOW(), INTERVAL {$default_login_lock} SECOND)")
                        ->first();

        return !is_null($last_attempt);
    }

    public static function isAttemptOver()
    {
        return static::getByIpAndUri()
                ->get()
                ->count() >= static::$LOGIN_ATTEMPT_LENGTH;
    }
}