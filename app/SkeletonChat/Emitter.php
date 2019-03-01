<?php

namespace SkeletonChatApp;

use Ratchet\ConnectionInterface;
use SkeletonChatApp\Models\ChatStatus;
use SkeletonChatApp\Models\Message;
use SkeletonChatApp\Models\User;
use SkeletonChatApp\Transformers\SendMessageTransformer;

class Emitter
{
    protected $clients;

    public function __construct() {
        $this->clients = [];
    }

    public function onConnectionEstablish(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);
        $auth_user = User::findByLoginToken($params['login_token']);

        $clients = $this->clients;

        foreach ($clients as $user_id => $client) {
            if ($client !== $from)
            {
                // if user have no chat status
                $result = !is_null($auth_user->chatStatus) ? $auth_user->chatStatus->setAsOnline() : ChatStatus::createOnlineUser($auth_user->id);

                $return_data = [
                    'event' => __FUNCTION__,
                    'success' => !is_null($result),
                    'auth_user_id' => $auth_user->id,
                    'token' => User::find($user_id)->login_token
                ];

                $client->send(json_encode($return_data));
            }
        }
    }

    public function onDisconnect(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);
        $auth_user = User::findByLoginToken($params['login_token']);

        $clients = $this->clients;

        foreach ($clients as $user_id => $client) {
            if ($client !== $from)
            {
                // if user have no chat status
                $result = !is_null($auth_user->chatStatus) ? $auth_user->chatStatus->setAsOffline() : ChatStatus::createOnlineUser($auth_user->id);

                $return_data = [
                    'event' => __FUNCTION__,
                    'success' => !is_null($result),
                    'auth_user_id' => $auth_user->id,
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

        $sentMessage = $chattingFrom->sendMessage(new Message([
            'message' => $msg->message,
            'receiver_id' => $chattingTo->id
        ]));

        if ($sentMessage)
        {
            $message = sklt_transformer($sentMessage, new SendMessageTransformer)->toArray();

            // self
            $return_data = [
                'event' => __FUNCTION__,
                'message' => $message['data'],
                'chatting_to_id' => $chattingTo->id,
                'token' => $chattingFrom->login_token
            ];

            $from->send(json_encode($return_data));
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
                'unread_number' => $receiver->numberOfUnread($sender->id),
                'token' => $receiver->login_token
            ];

            $receiverSocket = $this->clients[$receiver->id];
            $receiverSocket->send(json_encode($return_data));
        }
    }

    public function onTyping(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $auth_user = User::findByLoginToken($params['login_token']);
        $chattingTo = User::find($msg->chatting_to_id);

        // if chatting to is online
        if (isset($this->clients[$msg->chatting_to_id]))
        {
            $return_data = [
                'event' => __FUNCTION__,
                'chatting_from_id' => $auth_user->id,
                'chatting_to_id' => $msg->chatting_to_id,
                'token' => $chattingTo->login_token
            ];

            $chattingToSocket = $this->clients[$msg->chatting_to_id];
            $chattingToSocket->send(json_encode($return_data));
        }
    }

    public function onStopTyping(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $auth_user = User::findByLoginToken($params['login_token']);
        $chattingTo = User::find($msg->chatting_to_id);

        // if chatting to is online
        if (isset($this->clients[$msg->chatting_to_id]))
        {
            $return_data['event'] = __FUNCTION__;
            $return_data = [
                'event' => __FUNCTION__,
                'chatting_from_id' => $auth_user->id,
                'token' => $chattingTo->login_token
            ];

            $receiverSocket = $this->clients[$msg->chatting_to_id];
            $receiverSocket->send(json_encode($return_data));
        }
    }

    public function onReadMessage(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $auth_user = User::findByLoginToken($params['login_token']);
        $chattingTo = User::find($msg->chatting_to_id);

        $is_read = $auth_user->markUnreadMessageAsRead($msg->chatting_to_id);

        if (!is_null($is_read))
        {
            $conversation = $auth_user->conversation($msg->chatting_to_id)
                                ->select(["id", "message", "sender_id", "receiver_id", "created_at"])
                                ->orderBy('id', "DESC")
                                ->limit(config('sklt-chat.default_conversation_length'))
                                ->get()
                                ->sortBy('id');

            $conversation = sklt_transformer($conversation, new SendMessageTransformer)->toArray();

            $return_data = [
                'event' => __FUNCTION__,
                'chatting_to_id' => $chattingTo->id,
                'conversation' => $conversation['data'],
                'token' => $auth_user->login_token
            ];

            $from->send(json_encode($return_data));
        }
    }

    public function onLoadMoreMessages(ConnectionInterface $from, $msg)
    {
        $this->onReadMessage($from, $msg);

        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $auth_user = User::findByLoginToken($params['login_token']);
        $sender_id = $msg->sender_id;
        $load_more_increment = $msg->load_more_increment;

        $default_conversation_length = config('sklt-chat.default_conversation_length');

        $conversation = Message::conversation([$sender_id, $auth_user->id])
                            ->with(['sender', 'receiver'])
                            ->orderBy('id', "DESC")
                            ->offset($default_conversation_length * $load_more_increment)
                            ->limit($default_conversation_length)
                            ->get()
                            ->sortBy('id');

        $return_data = [
            'event' => __FUNCTION__,
            'conversation' => $conversation
        ];

        $from->send(json_encode($return_data));
    }
}
