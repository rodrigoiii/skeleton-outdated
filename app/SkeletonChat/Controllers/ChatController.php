<?php

namespace SkeletonChatApp\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonAuthApp\Auth;
use SkeletonChatApp\Models\Message;
use SkeletonChatApp\Models\User;
use SkeletonCore\BaseController;

class ChatController extends BaseController
{
    public function index(Response $response)
    {
        $auth_user = Auth::user();
        $contacts = User::contactsOrderByOnlineStatus($auth_user->id)->get();

        $active_contact = null;
        if ($contacts->isNotEmpty())
        {
            $active_contact = $contacts->first();
            $conversation = Message::conversation($auth_user->id, $active_contact->id);
        }
        else
        {
            $conversation = Message::conversation($auth_user->id);
        }

        $initial_conversation = $conversation->orderBy('id', "DESC")
                                ->limit(config('sklt-chat.default_conversation_length'))
                                ->get()
                                ->sortBy('id');

        return $this->view->render($response, "sklt-chat/chat.twig", compact('contacts', 'active_contact', 'initial_conversation'));
    }
}
