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

        return $this->view->render($response, "sklt-chat/chat.twig", compact('contacts'));
    }
}
