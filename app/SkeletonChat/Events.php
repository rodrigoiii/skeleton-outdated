<?php

namespace SkeletonChatApp;

use Ratchet\ConnectionInterface;
use SkeletonChatApp\Models\ChatStatus;
use SkeletonChatApp\Models\Message;
use SkeletonChatApp\Models\User;
use SkeletonChatApp\Transformers\SendMessageTransformer;

class Events
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

                $return_data['event'] = __FUNCTION__;
                $return_data['user_id'] = $auth_user->id;
                $return_data['success'] = !is_null($result);
                $return_data['token'] = User::find($user_id)->login_token;

                $client->send(json_encode($return_data));
            }
        }
    }

    public function onDisconnect(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);
        $auth_user = User::findByLoginToken($params['login_token']);

        $clients = $this->clients;

        foreach ($clients as $client) {
            if ($client !== $from)
            {
                $return_data['event'] = __FUNCTION__;
                $return_data['user_id'] = $auth_user->id;

                // if user have no chat status
                $result = !is_null($auth_user->chatStatus) ? $auth_user->chatStatus->setAsOffline() : ChatStatus::createOnlineUser($auth_user->id);

                $return_data['success'] = !is_null($result);

                $client->send(json_encode($return_data));
            }
        }
    }

    public function onSendMessage(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $user_sender = User::findByLoginToken($params['login_token']);
        $user_receiver = User::find($msg->receiver_id);

        $sent_message = $user_sender->sendMessage(new Message([
            'message' => $msg->message,
            'receiver_id' => $user_receiver->id
        ]));

        if ($sent_message)
        {
            $message = sklt_transformer($sent_message, new SendMessageTransformer)->toArray();

            // self
            $return_data['event'] = __FUNCTION__;
            $return_data['message'] = $message['data'];
            $return_data['token'] = $user_sender->login_token;

            $from->send(json_encode($return_data));
        }
    }

    public function onReceiveMessage(ConnectionInterface $from, $msg)
    {
        $message = $msg->message;
        $user_sender = User::find($message->sender->id);
        $user_receiver = User::find($message->receiver->id);

        // if receiver online
        if (isset($this->clients[$user_receiver->id]))
        {
            $receiver = $this->clients[$user_receiver->id];

            $return_data['event'] = __FUNCTION__;
            $return_data['message'] = $message;
            $return_data['number_unread'] = $user_receiver->numberOfUnread($user_sender->id);
            $return_data['token'] = $user_receiver->login_token;

            $receiver->send(json_encode($return_data));
        }
    }

    public function onTyping(ConnectionInterface $from, $msg)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $auth_user = User::findByLoginToken($params['login_token']);

        // mark unread as read
        $msg->sender_id = $msg->receiver_id;
        $this->onReadMessage($from, $msg);

        // if receiver online
        if (isset($this->clients[$msg->receiver_id]))
        {
            $return_data = [
                'event' => __FUNCTION__,
                'sender_id' => $auth_user->id
            ];

            $receiver = $this->clients[$msg->receiver_id];
            $receiver->send(json_encode($return_data));
        }
    }

    public function onStopTyping(ConnectionInterface $from, $msg)
    {
        // if receiver online
        if (isset($this->clients[$msg->receiver_id]))
        {
            $return_data['event'] = __FUNCTION__;

            $receiver = $this->clients[$msg->receiver_id];
            $receiver->send(json_encode($return_data));
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
            $return_data['event'] = __FUNCTION__;
            $return_data['sender_id'] = $sender_id;

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

        $return_data['event'] = __FUNCTION__;
        $return_data['conversation'] = $conversation;

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

        $return_data['event'] = __FUNCTION__;
        $return_data['conversation'] = $conversation;

        $from->send(json_encode($return_data));
    }
}
