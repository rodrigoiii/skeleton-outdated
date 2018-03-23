<?php

namespace AuthSlim\Middlewares;

use AuthSlim\Auth\Auth;

trait GuestMiddlewareTrait
{
    public function getFlash()
    {
        return $this->container->flash;
    }

    public function alreadLoggedInRedirect($response)
    {
        $this->getFlash()->addMessage('alert-message', "You are already logged in.");
        return $response->withRedirect($this->container->router->pathFor('auth.authenticated-page'));
    }

    public function __invoke($request, $response, $next)
    {
        if (Auth::check())
        {
            return $this->alreadLoggedInRedirect($response);
        }

        return $next($request, $response);
    }
}