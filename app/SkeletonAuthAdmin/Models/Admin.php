<?php

namespace App\SkeletonAuthAdmin\Models;

use App\SkeletonAuth\Models\User;

class Admin extends User
{
    protected $table = "users";

    /**
     * Define fillable columns to avoid
     * mass assignment exception.
     *
     * @var array
     */
    protected $fillable = ["picture", "first_name", "last_name", "email", "password", "is_admin", "login_token"];

    public function setLoginToken($login_token)
    {
        $this->login_token = $login_token;
        return $this->save();
    }

    /**
     * To filter all query for admin only
     *
     * @param  $query
     * @return Illuminate\Database\Eloquent\QueryBuilder
     */
    public function scopeAdmin($query)
    {
        return $query->where('is_admin', 1);
    }

    public static function findById($id)
    {
        return static::admin()->where('id', $id)->first();
    }

    public static function findByEmail($email)
    {
        return static::admin()->where('email', $email)->first();
    }
}
