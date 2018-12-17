<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    const TYPE_REGISTER = "register";
    const TYPE_RESET_PASSWORD = "reset-password";

    /**
     * Define fillable columns to avoid
     * mass assignment exception.
     *
     * @var array
     */
    protected $fillable = ["token", "is_used", "type", "payload"];

    /**
     * Return model id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function isTokenExpired($seconds)
    {
        return Carbon::now() >= Carbon::parse($this->created_at)->addSeconds($seconds);
    }

    public function isUsed()
    {
        return $this->is_used;
    }

    public function markTokenAsUsed()
    {
        $this->is_used = 1;
        return $this->save();
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public static function findByToken($token)
    {
        return static::where('token', $token)->get()->last();
    }

    public static function createRegisterType($payload)
    {
        $authToken = static::create([
            'token' => uniqid(),
            'type' => static::TYPE_REGISTER,
            'payload' => $payload
        ]);

        return $authToken;
    }

    public static function createResetPasswordType($payload)
    {
        $authToken = static::create([
            'token' => uniqid(),
            'type' => static::TYPE_RESET_PASSWORD,
            'payload' => $payload
        ]);

        return $authToken;
    }
}
