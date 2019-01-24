<?php

namespace App\SkeletonAuth\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * Define fillable columns to avoid
     * mass assignment exception.
     *
     * @var array
     */
    protected $fillable = ["picture", "first_name", "last_name", "email", "password"];

    /**
     * To filter all query for user only
     *
     * @param  $query
     * @return Illuminate\Database\Eloquent\QueryBuilder
     */
    public function scopeUser($query)
    {
        return $query->where('is_admin', 0);
    }

    public function setLoginToken($login_token)
    {
        $this->login_token = $login_token;
        return $this->save();
    }

    /**
     * Return model id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function getFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public static function findByEmail($email)
    {
        return static::user()->where('email', $email)->first();
    }
}
