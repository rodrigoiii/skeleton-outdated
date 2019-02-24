<?php

namespace SkeletonChatApp\Controllers;

use SkeletonChatApp\Models\Message;
use SkeletonChatApp\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonCore\BaseController;
use SkeletonAuthApp\Auth;

class ChatController extends BaseController
{
    public function index(Response $response)
    {
        $auth_user = Auth::user();
        $contacts = User::contactsOrderByOnlineStatus($auth_user->id)->get();

        $initial_conversation = Message::conversation([$auth_user->id, $contacts->isNotEmpty() ? $contacts[0]->id : null])
                                ->orderBy('id', "DESC")
                                ->limit(config('sklt-chat.default_conversation_length'))
                                ->get()
                                ->sortBy('id');

        return $this->view->render($response, "sklt-chat/chat.twig", compact('contacts', 'initial_conversation'));
    }
}
