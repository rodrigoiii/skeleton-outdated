<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use AuthSlim\Models\User as UserModel;

class User extends UserModel
{
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'auth_token'];
}