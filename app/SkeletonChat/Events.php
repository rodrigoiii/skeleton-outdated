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

    public function onConnectionEstablish(ConnectionInterface $from, $data)
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
                $result = !is_null($auth_user->chatStatus) ? $auth_user->chatStatus->setAsOnline() : ChatStatus::createOnlineUser($auth_user->id);

                $return_data['success'] = !is_null($result);

                $client->send(json_encode($return_data));
            }
        }
    }

    public function onDisconnect(ConnectionInterface $from, $data)
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

    public function onSendMessage(ConnectionInterface $from, $data)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $user_sender = User::findByLoginToken($params['login_token']);
        $user_receiver = User::find($data->receiver_id);

        $sent_message = $user_sender->sendMessage(new Message([
            'message' => $data->message,
            'receiver_id' => $user_receiver->id
        ]));

        if ($sent_message)
        {
            $message = sklt_transformer($sent_message, new SendMessageTransformer)->toArray();

            // self
            $return_data['event'] = __FUNCTION__;
            $return_data['message'] = $message['data'];

            $from->send(json_encode($return_data));

            // if receiver online
            if (isset($this->clients[$user_receiver->id]))
            {
                $receiver = $this->clients[$user_receiver->id];
                $return_data['number_unread'] = $user_receiver->numberOfUnread($user_sender->id);

                $receiver->send(json_encode($return_data));
            }
        }
    }

    public function onTyping(ConnectionInterface $from, $data)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $auth_user = User::findByLoginToken($params['login_token']);

        // mark unread as read
        $data->sender_id = $data->receiver_id;
        $this->onReadMessage($from, $data);

        // if receiver online
        if (isset($this->clients[$data->receiver_id]))
        {
            $return_data = [
                'event' => __FUNCTION__,
                'sender_id' => $auth_user->id
            ];

            $receiver = $this->clients[$data->receiver_id];
            $receiver->send(json_encode($return_data));
        }
    }

    public function onStopTyping(ConnectionInterface $from, $data)
    {
        // if receiver online
        if (isset($this->clients[$data->receiver_id]))
        {
            $return_data['event'] = __FUNCTION__;

            $receiver = $this->clients[$data->receiver_id];
            $receiver->send(json_encode($return_data));
        }
    }

    public function onReadMessage(ConnectionInterface $from, $data)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $receiver = User::findByLoginToken($params['login_token']);
        $sender_id = $data->sender_id;

        $is_marked = Message::markAsRead($sender_id, $receiver->id);
        if (!is_null($is_marked))
        {
            $return_data['event'] = __FUNCTION__;
            $return_data['sender_id'] = $sender_id;

            $from->send(json_encode($return_data));
        }
    }

    public function onFetchMessages(ConnectionInterface $from, $data)
    {
        $this->onReadMessage($from, $data);

        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $auth_user = User::findByLoginToken($params['login_token']);
        $sender_id = $data->sender_id;

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

    public function onLoadMoreMessages(ConnectionInterface $from, $data)
    {
        $this->onReadMessage($from, $data);

        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $auth_user = User::findByLoginToken($params['login_token']);
        $sender_id = $data->sender_id;
        $load_more_increment = $data->load_more_increment;

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
