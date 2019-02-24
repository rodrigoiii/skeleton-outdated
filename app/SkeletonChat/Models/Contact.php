<?php

namespace SkeletonChatApp\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ["contact_id", "user_id"];

    public function user()
    {
        return $this->belongsTo("SkeletonChatApp\Models\User");
    }

    public function contact()
    {
        return $this->belongsTo("SkeletonChatApp\Models\User", "contact_id");
    }
}
