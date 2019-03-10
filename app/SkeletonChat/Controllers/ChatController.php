<?php

namespace SkeletonChatApp\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonAuthApp\Auth;
use SkeletonChatApp\Models\Message;
use SkeletonChatApp\Models\User;
use SkeletonChatApp\Transformers\ContactsBothOfUserTransformer;
use SkeletonCore\BaseController;

class ChatController extends BaseController
{
    public function index(Response $response)
    {
        $user = User::find(Auth::user()->id);
        // $contacts = User::contactsOrderByOnlineStatus($auth_user->id)->get();

        $contacts = $user->contactsBothUser()->get();
        $contacts = sklt_transformer($contacts, new ContactsBothOfUserTransformer($user))->toArray()['data'];

        $contactRequests = $user->contactRequestsBothUser()->get();

        return $this->view->render($response, "sklt-chat/chat.twig", compact('contacts', 'contactRequests'));
    }
}
