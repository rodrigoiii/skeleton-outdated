<?php

namespace SkeletonChatApp\Middlewares\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonChatApp\Models\User;
use SkeletonCore\BaseMiddleware;

class UserApiMiddleware extends BaseMiddleware
{
    /**
     * Block the request of non logged in user
     *
     * @param  Request $request
     * @param  Response $response
     * @param  callable $next
     * @return callable
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $login_token = $request->getParam('login_token');

        if (!is_null($login_token))
        {
            $user = User::findByLoginToken($login_token);

            if (!is_null($user))
            {
                return $next($request, $response);
            }
        }

        return $response->withJson([
            'success' => false,
            'message' => "Unauthorized user"
        ]);
    }
}
