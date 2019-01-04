<?php

namespace App\Models;

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
        return static::where('email', $email)->first();
    }
}
