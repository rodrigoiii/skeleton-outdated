<?php

namespace SkeletonChatApp\Models;

use Illuminate\Database\Eloquent\Model;
use SkeletonChatApp\Models\Contact;
use SkeletonChatApp\Models\ContactRequest;
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

    public function contact_requests()
    {
        return $this->hasMany("SkeletonChatApp\Models\ContactRequest", "by_id");
    }

    public function contact_requests_from()
    {
        return $this->hasMany("SkeletonChatApp\Models\ContactRequest", "to_id");
    }

    public function contacts()
    {
        return $this->hasMany("SkeletonChatApp\Models\Contact", "owner_id");
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

    public function contactsBothUser()
    {
        return Contact::where('user_id', $this->id)
                ->orWhere('owner_id', $this->id);
    }

    public function contactRequestsBothUser()
    {
        return ContactRequest::where('by_id', $this->id)
                ->where('is_read_by', ContactRequest::IS_NOT_YET_READ)
                ->orWhere('to_id', $this->id)
                ->where('is_read_to', ContactRequest::IS_NOT_YET_READ);
    }

    public function sendContactRequest($user_id)
    {
        // send contact request
        $contactRequest = ContactRequest::create([
            'by_id' => $this->id,
            'to_id' => $user_id
        ]);

        return $contactRequest instanceof ContactRequest;
    }

    public function acceptRequest($user_id)
    {
        $contactRequest = $this->contact_requests_from()
                                ->notYetAccepted()
                                ->where('by_id', $user_id)
                                ->first();

        if (!is_null($contactRequest))
        {
            // accept the contact request
            if ($contactRequest->markAsAccepted())
            {
                $contactRequest->markAsUnread();
                $newContact = $this->addContact($user_id);

                return $newContact instanceof Contact;
            }
        }

        $user = static::find($user_id);
        \Log::error($user->getFullName() . " has no request to " . $this->getFullName());
        return false;
    }

    // public function addContactRequest($user_id)
    // {
    //     $contactRequest = $this->contact_requests_from()
    //                             ->notYetAccepted()
    //                             ->where('by_id', $user_id)
    //                             ->first();

    //     $result = false;

    //     // if contact to be add has pending request
    //     if (!is_null($contactRequest))
    //     {
    //         // accept the contact request
    //         if ($contactRequest->markAsAccepted())
    //         {
    //             $this->addContact($user_id);

    //             $contactRequest->markAsUnread();
    //             $result = ContactRequest::TYPE_ACCEPTED;
    //         }
    //     }
    //     else
    //     {
    //         // send contact request
    //         $contactRequest = ContactRequest::create([
    //             'by_id' => $this->id,
    //             'to_id' => $user_id
    //         ]);

    //         $result = ContactRequest::TYPE_REQUESTED;
    //     }

    //     return $result;
    // }

    public function addContact($user_id)
    {
        return Contact::create([
            'user_id' => $user_id,
            'owner_id' => $this->id
        ]);
    }

    // public function addContact($contact_id)
    // {
    //     $contact_request = $this->contactRequests()
    //                             ->where('user_id', $contact_id)
    //                             ->first();

    //     var_dump($contact_request);
    //     die;

    //     $result = false;

    //     // if contact to be add has pending request
    //     if (!is_null($contact_request))
    //     {
    //         // accept the contact request
    //         if ($contact_request->markAsAccepted())
    //         {
    //             Notification::createAcceptedNotification($this->id, $contact_id);
    //             $result = Notification::TYPE_ACCEPTED;
    //         }
    //     }
    //     else
    //     {
    //         // send contact request
    //         $contact = Contact::create([
    //             'contact_id' => $contact_id,
    //             'user_id' => $this->id
    //         ]);

    //         if ($contact instanceof Contact)
    //         {
    //             Notification::createRequestedNotification($this->id, $contact_id);
    //             $result = Notification::TYPE_REQUESTED;
    //         }
    //     }

    //     return $result;
    // }

    // public function officialContactsOfEachOther()
    // {
    //     return Contact::where('contact_id', $this->id)
    //             ->orWhere('user_id', $this->id);
    // }

    // public function officialContacts()
    // {
    //     return $this->contacts()->accepted();
    // }

    // public function userRequests()
    // {
    //     return $this->contacts()->notYetAccepted();
    // }

    // public function contactRequests()
    // {
    //     return Contact::where('contact_id', $this->id)->notYetAccepted();
    // }

    // public function userNotifications()
    // {
    //     return Notification::where('by_id', $this->id)
    //             ->where('is_read_by', Notification::IS_NOT_YET_READ)
    //             ->orWhere('to_id', $this->id)
    //             ->where('is_read_to', Notification::IS_NOT_YET_READ);
    // }

    // public function removeUserRequest($contact_id)
    // {
    //     $user_request = Contact::where('user_id', $this->id)
    //                         ->where('contact_id', $contact_id)
    //                         ->first();

    //     if (!is_null($user_request))
    //     {
    //         if ($user_request->isNotAccepted())
    //         {
    //             return $user_request->delete();
    //         }
    //         else
    //         {
    //             \Log::error("Error: Cannot remove user request. Request is already accepted.");
    //         }
    //     }
    //     else
    //     {
    //         \Log::error("Error: User contact request is not exist.");
    //     }

    //     return false;
    // }

    // public function findNotification($contact_id)
    // {
    //     return Notification::requested()
    //             ->where('by_id', $this->id)
    //             ->where('to_id', $contact_id)
    //             ->first();
    // }

    // public function markAsReadNotification()
    // {
    //     $read_notification_by = Notification::where('by_id', $this->id)
    //                             ->IsNotReadBy()
    //                             ->update(['is_read_by' => Notification::IS_READ]);

    //     $read_notification_to = Notification::where('to_id', $this->id)
    //                             ->IsNotReadTo()
    //                             ->update(['is_read_to' => Notification::IS_READ]);

    //     return $read_notification_by || $read_notification_to;
    // }

    public static function contactsOrderByOnlineStatus($auth_id)
    {
        return static::select(\DB::raw("users.*, chat_statuses.status"))
                    ->leftJoin('chat_statuses', "users.id", "=", "chat_statuses.user_id")
                    ->leftJoin('contacts', "users.id", "=", "contacts.user_id")
                    ->leftJoin(\DB::raw("
                        (SELECT m.* FROM messages m
                            LEFT JOIN messages m2 ON m.sender_id = m2.sender_id AND m2.id > m.id
                            WHERE m2.id IS NULL
                        ) m"), "users.id", "=", "m.sender_id")
                    ->where('contacts.owner_id', $auth_id)
                    ->orderByRaw("FIELD(m.is_read, ".Message::IS_READ.", ".Message::IS_UNREAD.") DESC,
                                FIELD(chat_statuses.status, '".ChatStatus::OFFLINE_STATUS."', '".ChatStatus::ONLINE_STATUS."') DESC,
                                m.created_at DESC");
    }

    public static function findByLoginToken($login_token)
    {
        return static::where('login_token', $login_token)->first();
    }
}
