<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use AuthSlim\Models\User as UserModel;

class User extends UserModel
{
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'auth_token'];

    public function getFullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public static function findByEmail($email)
    {
        return static::where('email', $email)->first();
    }
}