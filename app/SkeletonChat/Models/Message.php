<?php

namespace SkeletonChatApp\Models;

use Illuminate\Database\Eloquent\Model;
use SkeletonChatApp\Models\User;

class Message extends Model
{
    protected $fillable = ["message", "sender_id", "receiver_id", "is_read"];

    const IS_READ = 1;
    const IS_UNREAD = 0;

    public function sender()
    {
        return $this->belongsTo("SkeletonChatApp\Models\User", "sender_id");
    }

    public function receiver()
    {
        return $this->belongsTo("SkeletonChatApp\Models\User", "receiver_id");
    }
}
