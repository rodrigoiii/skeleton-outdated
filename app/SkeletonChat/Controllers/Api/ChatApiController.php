<?php

namespace SkeletonChatApp\Controllers\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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
                    ->where('id', "<>", $user->id)->get();

        return $response->withJson([
            'success' => true,
            'data' => $results
        ]);
    }
}
