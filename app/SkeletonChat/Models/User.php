<?php

namespace SkeletonChatApp\Models;

use Illuminate\Database\Eloquent\Model;
use SkeletonChatApp\Traits\FullTextSearch;

class User extends Model
{
    use FullTextSearch;

    protected $searchable = ["first_name", "last_name"];

    public function sent_messages()
    {
        return $this->hasMany("SkeletonChatApp\Models\Message", "sender_id");
    }

    public function received_messages()
    {
        return $this->hasMany("SkeletonChatApp\Models\Message", "receiver_id");
    }

    public function chatStatus()
    {
        return $this->hasOne("SkeletonChatApp\Models\ChatStatus");
    }

    public function contacts()
    {
        return $this->hasMany("SkeletonChatApp\Models\Contact");
    }

    public function getFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function numberOfUnread($sender_id)
    {
        return $this->received_messages()
                    ->where('sender_id', $sender_id)
                    ->where('is_read', 0)
                    ->get()
                    ->count();
    }

    public function sendMessage(Message $message)
    {
        $message->sender_id = $this->id;
        $is_sent = $message->save();

        return $is_sent ? $message : false;
    }

    public static function contactsOrderByOnlineStatus($auth_id)
    {
        return static::select(\DB::raw("users.*, chat_statuses.status"))
                    ->leftJoin('chat_statuses', "users.id", "=", "chat_statuses.user_id")
                    ->leftJoin('contacts', "users.id", "=", "contacts.contact_id")
                    ->leftJoin(\DB::raw("
                        (SELECT m.* FROM messages m
                            LEFT JOIN messages m2 ON m.sender_id = m2.sender_id AND m2.id > m.id
                            WHERE m2.id IS NULL
                        ) m"), "users.id", "=", "m.sender_id")
                    ->where('contacts.user_id', $auth_id)
                    ->orderByRaw("FIELD(m.is_read, ".Message::IS_READ.", ".Message::IS_UNREAD.") DESC,
                                FIELD(chat_statuses.status, '".ChatStatus::OFFLINE_STATUS."', '".ChatStatus::ONLINE_STATUS."') DESC,
                                m.created_at DESC");
    }

    public static function findByLoginToken($login_token)
    {
        return static::where('login_token', $login_token)->first();
    }
}
