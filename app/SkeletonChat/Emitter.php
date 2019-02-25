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

        $sender = User::findByLoginToken($params['login_token']);
        $receiver = User::find($msg->receiver_id);

        $sent_message = $sender->sendMessage(new Message([
            'message' => $msg->message,
            'receiver_id' => $receiver->id
        ]));

        if ($sent_message)
        {
            $message = sklt_transformer($sent_message, new SendMessageTransformer)->toArray();

            // self
            $return_data = [
                'event' => __FUNCTION__,
                'message' => $message['data'],
                'token' => $sender->login_token
            ];

            $from->send(json_encode($return_data));
        }
    }

    public function onReceiveMessage(ConnectionInterface $from, $msg)
    {
        $message = $msg->message;
        $sender = User::find($message->sender->id);
        $receiver = User::find($message->receiver->id);

        // if receiver online
        if (isset($this->clients[$receiver->id]))
        {
            $return_data = [
                'event' => __FUNCTION__,
                'message' => $message,
                'number_unread' => $receiver->numberOfUnread($sender->id),
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
        $receiver = User::find($msg->receiver_id);

        // // mark unread as read
        // $msg->sender_id = $msg->receiver_id;
        // $this->onReadMessage($from, $msg);

        // if receiver online
        if (isset($this->clients[$msg->receiver_id]))
        {
            $return_data = [
                'event' => __FUNCTION__,
                'sender_id' => $auth_user->id,
                'receiver_id' => $msg->receiver_id,
                'token' => $receiver->login_token
            ];

            $receiverSocket = $this->clients[$msg->receiver_id];
            $receiverSocket->send(json_encode($return_data));
        }
    }

    public function onStopTyping(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $auth_user = User::findByLoginToken($params['login_token']);

        // if receiver online
        if (isset($this->clients[$msg->receiver_id]))
        {
            $return_data['event'] = __FUNCTION__;
            $return_data = [
                'event' => __FUNCTION__,
                'sender_id' => $auth_user->id
            ];

            $receiverSocket = $this->clients[$msg->receiver_id];
            $receiverSocket->send(json_encode($return_data));
        }
    }

    public function onReadMessage(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $receiver = User::findByLoginToken($params['login_token']);
        $sender_id = $msg->sender_id;

        $is_marked = Message::markAsRead($sender_id, $receiver->id);
        if (!is_null($is_marked))
        {
            $return_data = [
                'event' => __FUNCTION__,
                'sender_id' => $sender_id
            ];

            $from->send(json_encode($return_data));
        }
    }

    public function onFetchMessages(ConnectionInterface $from, $msg)
    {
        $this->onReadMessage($from, $msg);

        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $auth_user = User::findByLoginToken($params['login_token']);
        $sender_id = $msg->sender_id;

        $conversation = Message::conversation([$sender_id, $auth_user->id])
                            ->with(['sender', 'receiver'])
                            ->orderBy('id', "DESC")
                            ->limit(config('sklt-chat.default_conversation_length'))
                            ->get()
                            ->sortBy('id');

        $return_data = [
            'event' => __FUNCTION__,
            'conversation' => $conversation
        ];

        $from->send(json_encode($return_data));
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
