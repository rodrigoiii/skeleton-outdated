<?php

namespace SkeletonAuth\Traits\Middlewares;

use SkeletonAuthApp\Auth;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonCore\BaseMiddleware;

trait Guest
{
    /**
     * Block the request of logged in user
     *
     * @param  Request $request
     * @param  Response $response
     * @param  callable $next
     * @return callable
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if (!Auth::check())
        {
            return $next($request, $response);
        }

        return $this->redirectHandler($response);
    }

    public function redirectHandler(Response $response)
    {
        return $response->withRedirect($this->router->pathFor('auth.home'));
    }
}
