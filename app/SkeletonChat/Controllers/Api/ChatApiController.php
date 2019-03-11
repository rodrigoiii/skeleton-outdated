<?php

namespace SkeletonChatApp\Controllers\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonChatApp\Models\Contact;
use SkeletonChatApp\Models\ContactRequest;
use SkeletonChatApp\Models\Message;
use SkeletonChatApp\Models\Notification;
use SkeletonChatApp\Models\User;
use SkeletonChatApp\Transformers\ContactsRequestTransformer;
use SkeletonChatApp\Transformers\SearchContactsTransformer;
use SkeletonChatApp\Transformers\SendMessageTransformer;
use SkeletonChatApp\Transformers\UserRequestTransformer;
use SkeletonCore\BaseController;

class ChatApiController extends BaseController
{
    public function readMessages(Request $request, Response $response, $chatting_to_id)
    {
        $login_token = $request->getParam('login_token');
        $authUser = User::findByLoginToken($login_token);

        $is_read = Message::markAsRead($authUser->id, $chatting_to_id);

        return $response->withJson(
            $is_read ?
            [
                'success' => true,
                'message' => "Successfully read message."
            ] :
            [
                'success' => false,
                'message' => "Cannot read message this time. Please try again later."
            ]
        );
    }

    public function fetchConversation(Request $request, Response $response, $chatting_to_id)
    {
        $login_token = $request->getParam('login_token');
        $authUser = User::findByLoginToken($login_token);

        $conversation = Message::conversation($authUser->id, $chatting_to_id)
                            ->select(["id", "message", "sender_id", "receiver_id", "created_at"])
                            ->orderBy('id', "DESC")
                            ->limit(config('sklt-chat.default_conversation_length'))
                            ->get()
                            ->sortBy('id');

        $conversation = sklt_transformer($conversation, new SendMessageTransformer)->toArray();

        return $response->withJson([
            'success' => true,
            'message' => "Successfully fetch message.",
            'conversation' => $conversation['data']
        ]);
    }

    public function sendMessage(Request $request, Response $response, $chatting_to_id)
    {
        $login_token = $request->getParam('login_token');
        $chattingFrom = User::findByLoginToken($login_token);
        $chattingTo = User::find($chatting_to_id);

        $message = $request->getParam('message');

        $sentMessage = $chattingFrom->sendMessage(new Message([
            'message' => $message,
            'receiver_id' => $chattingTo->id
        ]));

        if ($sentMessage instanceof Message)
        {
            return $response->withJson([
                'success' => true,
                'message' => "Successfully send message.",
                'sent_message' => [
                    'id' => $sentMessage->id,
                    'message' => $sentMessage->message
                ]
            ]);
        }

        return $response->withJson([
            'success' => true,
            'message' => "Cannot send message this time. Please try again later."
        ]);
    }

    public function loadMoreMessages(Request $request, Response $response, $chatting_to_id)
    {
        $login_token = $request->getParam('login_token');
        $authUser = User::findByLoginToken($login_token);

        $load_more_counter = $request->getParam('load_more_counter');

        $default_convo_length = config('sklt-chat.default_conversation_length');

        $conversation = Message::conversation($authUser->id, $chatting_to_id)
                            ->select(["id", "message", "sender_id", "receiver_id", "created_at"])
                            ->orderBy('id', "DESC")
                            ->offset($default_convo_length * $load_more_counter)
                            ->limit($default_convo_length)
                            ->get()
                            ->sortBy('id');

        $conversation = sklt_transformer($conversation, new SendMessageTransformer)->toArray();

        return $response->withJson([
            'success' => true,
            'message' => "Successfully load more messages.",
            'conversation' => $conversation['data']
        ]);
    }

    // ok
    public function searchContacts(Request $request, Response $response)
    {
        $login_token = $request->getParam('login_token');
        $authUser = User::findByLoginToken($login_token);

        $keyword = $request->getParam('keyword');

        $contact_ids = $authUser->contacts()->pluck('user_id')->toArray();
        $contact_requests_ids = $authUser->contact_requests()->pluck('to_id')->toArray();

        // unsearchable ids
        $ignore_user_ids = array_flatten([$contact_ids, $contact_requests_ids, $authUser->id]);

        $results = User::search($keyword)
                    ->whereNotIn('id', $ignore_user_ids)
                    ->get();

        $results = sklt_transformer($results, new SearchContactsTransformer($authUser))->toArray();

        return $response->withJson([
            'success' => true,
            'users' => $results['data']
        ]);
    }

    public function sendContactRequest(Request $request, Response $response)
    {
        $login_token = $request->getParam('login_token');
        $authUser = User::findByLoginToken($login_token);
        $to_id = $request->getParam('to_id');

        $is_sent = ContactRequest::send($authUser->id, $to_id);

        return $response->withJson($is_sent ?
            [
                'success' => true,
                'message' => "Successfully send request."
            ] :
            [
                'success' => false,
                'message' => "Cannot send request this time. Please try again later."
            ]
        );
    }

    public function acceptContactRequest(Request $request, Response $response)
    {
        $login_token = $request->getParam('login_token');
        $authUser = User::findByLoginToken($login_token);

        $from_id = $request->getParam('from_id');
        $requestedBy = User::find($from_id);

        $is_accept = $authUser->acceptRequest($from_id);

        return $response->withJson($is_accept ?
            [
                'success' => true,
                'message' => "Successfully accept request.",
                'user' => [
                    'picture' => $requestedBy->picture,
                    'full_name' => $requestedBy->getFullName()
                ]
            ] :
            [
                'success' => false,
                'message' => "Cannot accept request this time. Please try again later."
            ]
        );
    }

    // public function contactRequests(Request $request, Response $response)
    // {
    //     $login_token = $request->getParam('login_token');
    //     $user = User::findByLoginToken($login_token);

    //     // $user_requests = sklt_transformer($user->userRequests()->get(), new UserRequestTransformer)->toArray();
    //     // $contact_requests = sklt_transformer($user->contactRequests()->get(), new ContactsRequestTransformer)->toArray();

    //     return $response->withJson([
    //         'success' => true,
    //         'user_requests' => $user_requests['data'],
    //         'contact_requests' => $contact_requests['data']
    //     ]);
    // }

    // ok
    // public function addContactRequest(Request $request, Response $response)
    // {
    //     $login_token = $request->getParam('login_token');
    //     $user_id = $request->getParam('user_id');

    //     $authUser = User::findByLoginToken($login_token);

    //     $contact_type = $authUser->addContactRequest($user_id);

    //     switch ($contact_type) {
    //         case ContactRequest::TYPE_ACCEPTED:
    //             $authUser = User::find($user_id);

    //             $data = [
    //                 'success' => true,
    //                 'message' => "Successfully add contact.",
    //                 'type' => ContactRequest::TYPE_ACCEPTED,
    //                 'user' => [
    //                     'picture' => $authUser->picture,
    //                     'full_name' => $authUser->getFullName()
    //                 ]
    //             ];
    //             break;

    //         case ContactRequest::TYPE_REQUESTED:
    //             $data = [
    //                 'success' => true,
    //                 'message' => "Successfully send request.",
    //                 'type' => ContactRequest::TYPE_REQUESTED
    //             ];
    //             break;

    //         default:
    //             $data = [
    //                 'success' => false,
    //                 'message' => "Cannot add contact this time. Please try again later."
    //             ];
    //             break;
    //     }

    //     return $response->withJson($data);
    // }

    /**
     * @param Request  $request
     * @param Response $response
     * @param integer   $contact_id id of table contacts
     */
    // public function removeRequest(Request $request, Response $response, $contact_id)
    // {
    //     $login_token = $request->getParam('login_token');
    //     $user = User::findByLoginToken($login_token);
    //     $contact = Contact::find($contact_id);

    //     if (!is_null($contact))
    //     {
    //         $is_deleted = $user->removeUserRequest($contact->contact_id);

    //         if ($is_deleted)
    //         {
    //             $notification = $user->findNotification($contact->contact_id);

    //             return $response->withJson([
    //                 'success' => true,
    //                 'message' => "Successfully remove user request.",
    //                 'notification_id' => !is_null($notification) ? $notification->id : null
    //             ]);
    //         }
    //     }

    //     return $response->withJson([
    //         'success' => false,
    //         'message' => "Cannot remove user request this time. Please try again later."
    //     ]);
    // }

    // public function readNotification(Request $request, Response $response)
    // {
    //     $login_token = $request->getParam('login_token');
    //     $user = User::findByLoginToken($login_token);

    //     $is_read = $user->markAsReadNotification();

    //     return $response->withJson(
    //     $is_read ?
    //     [
    //         'success' => true,
    //         'message' => "Successfully read notification.",
    //     ] :
    //     [
    //         'success' => false,
    //         'message' => "Cannot read notification this time. Please try again later."
    //     ]);
    // }

    // public function removeNotification(Request $request, Response $response, $notification_id)
    // {
    //     $login_token = $request->getParam('login_token');
    //     $user = User::findByLoginToken($login_token);

    //     $notification = Notification::find($notification_id);
    //     $is_deleted = !is_null($notification) ? $notification->delete() : false;

    //     return $response->withJson(
    //         $is_deleted ?
    //         [
    //             'success' => true,
    //             'message' => "Successfully remove request notification."
    //         ] :
    //         [
    //             'success' => false,
    //             'message' => "Cannot remove user request this time. Please try again later."
    //         ]
    //     );
    // }
}
