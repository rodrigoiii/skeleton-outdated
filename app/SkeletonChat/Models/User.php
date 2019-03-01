<?php

namespace SkeletonChatApp\Models;

use Illuminate\Database\Eloquent\Model;
use SkeletonChatApp\Models\Contact;
use SkeletonChatApp\Models\Message;
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

    public function conversation($receiver_id)
    {
        return Message::where('sender_id', $this->id)
                ->where('receiver_id', $receiver_id)
                ->orWhere('sender_id', $receiver_id)
                ->where('receiver_id', $this->id);
    }

    public function sendMessage(Message $message)
    {
        $message->sender_id = $this->id;
        $is_sent = $message->save();

        return $is_sent ? $message : false;
    }

    public function markUnreadMessageAsRead($sender_id)
    {
        return $this->received_messages()
            ->where('sender_id', $sender_id)
            ->update(['is_read' => 1]);
    }

    public function addContact($contact_id)
    {
        $pending_request = $this->contactRequests()
                                ->where('user_id', $contact_id)
                                ->first();

        // if contact to be add has pending request
        if (!is_null($pending_request))
        {
            $result = $pending_request->accepted() ? Contact::TYPE_ACCEPTED : false;
        }
        else
        {
            $contact = Contact::create([
                'contact_id' => $contact_id,
                'user_id' => $this->id
            ]);

            $result = $contact instanceof Contact ? Contact::TYPE_REQUESTED : false;
        }

        return $result;
    }

    public function officialContactsOfEachOther()
    {
        return Contact::where('contact_id', $this->id)
                ->orWhere('user_id', $this->id)
                ->accepted();
    }

    public function officialContacts()
    {
        return $this->contacts()->accepted();
    }

    public function userRequests()
    {
        return $this->contacts()->notYetAccepted();
    }

    public function contactRequests()
    {
        return Contact::where('contact_id', $this->id)->notYetAccepted();
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
