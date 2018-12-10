<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    /**
     * Define fillable columns to avoid
     * mass assignment exception.
     *
     * @var array
     */
    protected $fillable = ['token', 'is_used', 'payload'];

    /**
     * Return model id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
