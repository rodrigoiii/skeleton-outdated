<?php

namespace SkeletonChatApp\Controllers\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonChatApp\Models\Contact;
use SkeletonChatApp\Models\User;
use SkeletonCore\BaseController;

class ChatApiController extends BaseController
{
    public function searchContacts(Request $request, Response $response)
    {
        $login_token = $request->getParam('login_token');
        $user = User::findByLoginToken($login_token);

        $keyword = $request->getParam('keyword');

        $results = User::search($keyword)
                    ->select(\DB::raw("id, picture, first_name, last_name"))
                    ->whereNotIn('id', [$user->id, $user->contacts->pluck('contact_id')])->get();

        return $response->withJson([
            'success' => true,
            'data' => $results
        ]);
    }

    public function addContact(Request $request, Response $response, $contact_id)
    {
        $login_token = $request->getParam('login_token');
        $user = User::findByLoginToken($login_token);

        $contact = Contact::create([
            'contact_id' => $contact_id,
            'user_id' => $user->id
        ]);

        return $response->withJson($contact instanceof Contact ?
            [
                'success' => true,
                'message' => "Successfully add contact."
            ] :
            [
                'success' => false,
                'message' => "Cannot add contact this time. Please try again later."
            ]
        );
    }
}
