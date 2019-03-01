<?php

namespace SkeletonChatApp\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ["contact_id", "user_id", "is_accepted"];

    const IS_ACCEPTED = 1;
    const IS_NOT_YET_ACCEPTED = 0;

    const TYPE_ACCEPTED = "accepted";
    const TYPE_REQUESTED = "requested";

    public function user()
    {
        return $this->belongsTo("SkeletonChatApp\Models\User");
    }

    public function contact()
    {
        return $this->belongsTo("SkeletonChatApp\Models\User", "contact_id");
    }

    public function scopeAccepted($query)
    {
        return $query->where('is_accepted', static::IS_ACCEPTED);
    }

    public function scopeNotYetAccepted($query)
    {
        return $query->where('is_accepted', static::IS_NOT_YET_ACCEPTED);
    }

    public function accepted()
    {
        $this->is_accepted = static::IS_ACCEPTED;
        return $this->save();
    }

    public static function getByUserId($user_id)
    {
        return static::where('user_id', $user_id)->get();
    }
}
