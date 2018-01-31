<?php

namespace Framework\Auth\Models;

use Framework\Auth\Bridge;

trait UserTrait
{
    public function getFullname()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function setAuthToken($token)
    {
        $this->auth_token = $token;
        $this->save();
    }

    public static function getByEmailAndPassword($email, $password)
    {
        $User = Bridge::model('User');

        $user = $User::where('email', $email)->first();

        if (!is_null($user))
            return password_verify(sha1($password), $user->password) ? $user : null;

        return null;
    }
}