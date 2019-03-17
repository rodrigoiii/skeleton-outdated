<?php

namespace SkeletonChatApp;

use Ratchet\ConnectionInterface;
use SkeletonChatApp\Models\ChatStatus;
use SkeletonChatApp\Models\Message;
use SkeletonChatApp\Models\User;
use SkeletonChatApp\Transformers\ConversationTransformer;

class EventHandler
{
    protected $clients;

    public function __construct() {
        $this->clients = [];
    }

    public function onConnectionEstablish(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $authUser = User::findByLoginToken($params['login_token']);

        $clients = $this->clients;

        foreach ($clients as $user_id => $client) {
            if ($client !== $from)
            {
                // if user have no chat status
                $result = !is_null($authUser->chatStatus) ? $authUser->chatStatus->setAsOnline() : ChatStatus::createOnlineUser($authUser->id);

                $return_data = [
                    'event' => __FUNCTION__,
                    'success' => !is_null($result),
                    'auth_user_id' => $authUser->id,
                    'token' => User::find($user_id)->login_token
                ];

                $client->send(json_encode($return_data));
            }
        }
    }

    public function onDisconnect(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $authUser = User::findByLoginToken($params['login_token']);

        $clients = $this->clients;

        foreach ($clients as $user_id => $client) {
            if ($client !== $from)
            {
                // if user have no chat status
                $result = !is_null($authUser->chatStatus) ? $authUser->chatStatus->setAsOffline() : ChatStatus::createOnlineUser($authUser->id);

                $return_data = [
                    'event' => __FUNCTION__,
                    'success' => !is_null($result),
                    'auth_user_id' => $authUser->id,
                    'token' => User::find($user_id)->login_token
                ];

                $client->send(json_encode($return_data));
            }
        }
    }

    public function onSendMessage(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $chattingFrom = User::findByLoginToken($params['login_token']);
        $chattingTo = User::find($msg->chatting_to_id);

        $message = Message::find($msg->message_id);

        if (!is_null($message))
        {
            $message = sklt_transformer($message, new ConversationTransformer)->toArray();

            // if receiver online
            if (isset($this->clients[$chattingTo->id]))
            {
                $return_data = [
                    'event' => __FUNCTION__,
                    'message' => $message['data'],
                    'unread_number' => Message::numberOfUnread($chattingFrom->id, $chattingTo->id),
                    'token' => $chattingTo->login_token
                ];

                $chattingToSocket = $this->clients[$chattingTo->id];
                $chattingToSocket->send(json_encode($return_data));
            }
        }
    }

    public function onReceiveMessage(ConnectionInterface $from, $msg)
    {
        $message = $msg->message;
        $sender = User::find($message->sender->id);
        $receiver = User::find($msg->chatting_to_id);

        // if receiver online
        if (isset($this->clients[$receiver->id]))
        {
            $return_data = [
                'event' => __FUNCTION__,
                'message' => $message,
                'unread_number' => Message::numberOfUnread($sender->id, $receiver->id),
                'token' => $receiver->login_token
            ];

            $receiverSocket = $this->clients[$receiver->id];
            $receiverSocket->send(json_encode($return_data));
        }
    }

    public function onTyping(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $authUser = User::findByLoginToken($params['login_token']);
        $chattingTo = User::find($msg->chatting_to_id);

        // if chatting to is online
        if (isset($this->clients[$msg->chatting_to_id]))
        {
            $return_data = [
                'event' => __FUNCTION__,
                'chatting_from' => [
                    'id' => $authUser->id,
                    'picture' => $authUser->picture
                ],
                'token' => $chattingTo->login_token
            ];

            $chattingToSocket = $this->clients[$msg->chatting_to_id];
            $chattingToSocket->send(json_encode($return_data));
        }
    }

    public function onStopTyping(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $authUser = User::findByLoginToken($params['login_token']);
        $chattingTo = User::find($msg->chatting_to_id);

        // if chatting to is online
        if (isset($this->clients[$msg->chatting_to_id]))
        {
            $return_data = [
                'event' => __FUNCTION__,
                'chatting_from_id' => $authUser->id,
                'token' => $chattingTo->login_token
            ];

            $receiverSocket = $this->clients[$msg->chatting_to_id];
            $receiverSocket->send(json_encode($return_data));
        }
    }

    public function onSendContactRequest(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $authUser = User::findByLoginToken($params['login_token']);
        $requestTo = User::find($msg->to_id);

        // if the user that would receive contact request is online
        if (isset($this->clients[$msg->to_id]))
        {
            $return_data = [
                'event' => __FUNCTION__,
                'token' => $requestTo->login_token
            ];

            $requestToSocket = $this->clients[$msg->to_id];
            $requestToSocket->send(json_encode($return_data));
        }
    }

    // public function onReadMessage(ConnectionInterface $from, $msg)
    // {
    //     parse_str($from->httpRequest->getUri()->getQuery(), $params);

    //     $authUser = User::findByLoginToken($params['login_token']);

    //     $is_read = $authUser->markUnreadMessageAsRead($msg->chatting_to_id);

    //     if (!is_null($is_read))
    //     {
    //         $return_data = [
    //             'event' => __FUNCTION__,
    //             'chatting_to_id' => $msg->chatting_to_id,
    //             'token' => $authUser->login_token
    //         ];

    //         $from->send(json_encode($return_data));
    //     }
    // }

    // public function onFetchMessage(ConnectionInterface $from, $msg)
    // {
    //     parse_str($from->httpRequest->getUri()->getQuery(), $params);

    //     $authUser = User::findByLoginToken($params['login_token']);

    //     $conversation = $authUser->conversation($msg->chatting_to_id)
    //                         ->select(["id", "message", "sender_id", "receiver_id", "created_at"])
    //                         ->orderBy('id', "DESC")
    //                         ->limit(config('sklt-chat.default_conversation_length'))
    //                         ->get()
    //                         ->sortBy('id');

    //     $conversation = sklt_transformer($conversation, new ConversationTransformer)->toArray();

    //     $return_data = [
    //         'event' => __FUNCTION__,
    //         'conversation' => $conversation['data'],
    //         'token' => $authUser->login_token
    //     ];

    //     $from->send(json_encode($return_data));
    // }

    // public function onLoadMoreMessages(ConnectionInterface $from, $msg)
    // {
    //     parse_str($from->httpRequest->getUri()->getQuery(), $params);

    //     $authUser = User::findByLoginToken($params['login_token']);

    //     $default_convo_length = config('sklt-chat.default_conversation_length');

    //     $conversation = $authUser->conversation($msg->chatting_to_id)
    //                         ->select(["id", "message", "sender_id", "receiver_id", "created_at"])
    //                         ->orderBy('id', "DESC")
    //                         ->offset($default_convo_length * $msg->load_more_counter)
    //                         ->limit($default_convo_length)
    //                         ->get()
    //                         ->sortBy('id');

    //     $conversation = sklt_transformer($conversation, new ConversationTransformer)->toArray();

    //     $return_data = [
    //         'event' => __FUNCTION__,
    //         'conversation' => $conversation['data'],
    //         'token' => $authUser->login_token
    //     ];

    //     $from->send(json_encode($return_data));
    // }
}
