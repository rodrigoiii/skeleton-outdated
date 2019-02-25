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

    /**
     * Conversation of two users
     *
     * @param  int $sender_id
     * @param  int $sender_id
     * @return collection
     */
    public static function conversation($sender_id=null, $receiver_id=null)
    {
        return static::where('sender_id', $sender_id)
                ->where('receiver_id', $receiver_id)
                ->orWhere('sender_id', $receiver_id)
                ->where('receiver_id', $sender_id);
    }

    public static function sendMessage($message, $sender_id, $receiver_id)
    {
        return static::create([
            'message' => $message,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id
        ]);
    }

    public static function markAsRead($sender_id, $receiver_id)
    {
        return static::where('sender_id', $sender_id)
                    ->where('receiver_id', $receiver_id)
                    ->update(['is_read' => 1]);
    }

    public static function messageWithSenderAndReceiver($id)
    {
        return static::with(['sender', 'receiver'])
                    ->where('id', $id)
                    ->first();
    }
}
