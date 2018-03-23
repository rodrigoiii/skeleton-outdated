<?php

namespace AuthSlim\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'auth_token'];

    public function setAuthToken($token)
    {
        $this->auth_token = $token;
        $this->save();
    }

    public function getFullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function changePassword($new_password)
    {
        $this->password = password_hash($new_password, PASSWORD_DEFAULT);
        return $this->save();
    }

    public static function getByEmailAndPassword($email, $password)
    {
        $user = static::where('email', $email)->first();

        if (!is_null($user))
            return password_verify($password, $user->password) ? $user : null;

        return null;
    }

    public static function findByEmail($email)
    {
        return static::where('email', $email)->first();
    }
}