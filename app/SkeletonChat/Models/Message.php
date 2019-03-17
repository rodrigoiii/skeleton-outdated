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
        return $this->belongsTo(User::class, "sender_id");
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, "receiver_id");
    }

    public static function conversation($sender_id, $receiver_id)
    {
        return static::where(function($query) {
                    $query->where('sender_id', $sender_id);
                    $query->where('receiver_id', $receiver_id);
                })
                ->orWhere(function($query) {
                    $query->where('sender_id', $receiver_id);
                    $query->where('receiver_id', $sender_id);
                });
    }

    public static function numberOfUnread($sender_id, $receiver_id)
    {
        return static::where('sender_id', $sender_id)
                    ->where('receiver_id', $receiver_id)
                    ->where('is_read', static::IS_UNREAD)
                    ->get()
                    ->count();
    }

    public static function markAsRead($sender_id, $receiver_id)
    {
        return static::where('sender_id', $sender_id)
                    ->where('receiver_id', $receiver_id)
                    ->where('is_read', static::IS_UNREAD)
                    ->update(['is_read', static::READ]);
    }
}
