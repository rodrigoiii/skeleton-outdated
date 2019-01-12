<?php

namespace SkeletonAuthAdmin\Middlewares;

use App\SkeletonAuthAdmin\Auth;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonCore\BaseMiddleware;

trait AdminTrait
{
    /**
     * Block the request of non logged in admin
     *
     * @param  Request $request
     * @param  Response $response
     * @param  callable $next
     * @return callable
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if (Auth::check())
        {
            $this->view->getEnvironment()->addGlobal('auth_admin', Auth::admin());
            return $next($request, $response);
        }

        return $this->redirectHandler($response);
    }

    public function redirectHandler(Response $response)
    {
        $this->flash->addMessage('error', "Unauthorized to access the page!");
        return $response->withRedirect($this->router->pathFor('auth-admin.login'));
    }
}
