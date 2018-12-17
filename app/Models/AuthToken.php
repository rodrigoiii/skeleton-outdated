<?php

namespace App\Models;

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

    public function createRegisterType($payload)
    {
        $authToken = AuthToken::create([
            'token' => uniqid(),
            'type' => static::TYPE_REGISTER,
            'payload' => $payload
        ]);

        return $authToken;
    }

    public function createResetPasswordType($payload)
    {
        $authToken = AuthToken::create([
            'token' => uniqid(),
            'type' => static::TYPE_RESET_PASSWORD,
            'payload' => $payload
        ]);

        return $authToken;
    }
}
