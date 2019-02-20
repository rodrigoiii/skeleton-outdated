<?php

namespace SkeletonChatApp\Models;

use Illuminate\Database\Eloquent\Model;
use SkeletonAuthApp\Auth;

class User extends Model
{
    protected $fillable = ["picture", "first_name", "last_name", "email", "password", "auth_token"];

    public function messages()
    {
        return $this->hasMany('SkeletonChatApp\Models\Message', "sender_id");
    }

    public function chatStatus()
    {
        return $this->hasOne('SkeletonChatApp\Models\ChatStatus');
    }

    public function numberOfUnread($receiver_id)
    {
        return $this->messages()
                    ->where('receiver_id', $receiver_id)
                    ->where('is_read', 0)
                    ->get()
                    ->count();
    }

    public static function contacts()
    {
        return static::where('id', "<>", Auth::user()->id);
    }

    public static function contactsOrderByOnlineStatus()
    {
        return static::select(\DB::raw("users.*, chat_statuses.status"))
                    ->leftJoin('chat_statuses', "users.id", "=", "chat_statuses.user_id")
                    ->leftJoin(\DB::raw("
                        (SELECT m.* FROM messages m
                            LEFT JOIN messages m2 ON m.sender_id = m2.sender_id AND m2.id > m.id
                            WHERE m2.id IS NULL
                        ) m"), "users.id", "=", "m.sender_id")
                    ->where('users.id', "<>", Auth::user()->id)
                    ->orderByRaw("FIELD(m.is_read, ".Message::IS_READ.", ".Message::IS_UNREAD.") DESC,
                                FIELD(chat_statuses.status, '".ChatStatus::OFFLINE_STATUS."', '".ChatStatus::ONLINE_STATUS."') DESC,
                                m.created_at DESC");
    }
}
