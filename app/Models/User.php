<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * Define fillable columns to avoid
     * mass assignment exception
     *
     * @var array
     */
    protected $fillable = ["first_name", "last_name", "email"];

    /**
     * Return table id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Update the user info and return if data
     * were modified
     *
     * @param  integer $id
     * @param  array $data
     * @return boolean
     */
    public static function _update($id, array $data)
    {
        $user = static::find($id);
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];

        $has_change = $user->isDirty();
        $user->save();

        return $has_change;
    }
}
