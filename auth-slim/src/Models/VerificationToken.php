<?php

namespace AuthSlim\Models;

use AuthSlim\Utilities\EncryptDecrypt;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VerificationToken extends Model
{
    const TYPE_REGISTER = "register";
    const TYPE_RESET_PASSWORD = "reset-password";

    protected $fillable = ['type', 'token', 'data', 'is_verified'];

    public static $REGISTER_REQUEST_EXPIRATION = 60 * 60 * 5; // 5 hours
    public static $RESET_PASSWORD_REQUEST_EXPIRATION = 60 * 60 * 5; // 5 hours

    public function isTokenForRegisterExpired()
    {
        return Carbon::parse($this->created_at)
                ->addSeconds(static::$REGISTER_REQUEST_EXPIRATION) <= Carbon::now();
    }

    public function isTokenForResetPasswordExpired()
    {
        return Carbon::parse($this->created_at)
                ->addSeconds(static::$RESET_PASSWORD_REQUEST_EXPIRATION) <= Carbon::now();
    }

    public function isVerified()
    {
        return $this->is_verified;
    }

    public function verify()
    {
        $this->is_verified = 1;
        return $this->save();
    }

    public function getDecryptData()
    {
        return json_decode(EncryptDecrypt::decrypt($this->data, config('app.key')));
    }

    public static function tokenExist($token)
    {
        return static::where('token', $token)->get()->isNotEmpty();
    }

    public static function findByToken($token)
    {
        return static::where('token', $token)
                ->orderBy('created_at', "DESC")
                ->first();
    }

    public static function encryptData(array $data)
    {
        return EncryptDecrypt::encrypt(json_encode($data), config('app.key'));
    }
}