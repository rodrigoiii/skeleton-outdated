<?php

namespace Framework\Auth\Middlewares;

use Framework\Auth\Auth\Auth;
use Framework\Auth\Bridge;

trait GuestTrait
{
    public function alreadyLogin($response)
    {
        $this->flash->addMessage('info', "You are already logged in.");
        return $response->withRedirect($this->router->pathFor('auth.home'));
    }

    public function __invoke($request, $response, $next)
    {
        if (Auth::check())
        {
            return $this->alreadyLogin($response);
        }

        return $next($request, $response);
    }
}