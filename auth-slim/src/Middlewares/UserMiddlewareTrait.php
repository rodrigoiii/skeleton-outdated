<?php

namespace AuthSlim\Middlewares;

use AuthSlim\Auth\Auth;

trait UserMiddlewareTrait
{
    public function getFlash()
    {
        return $this->container->flash;
    }

    public function getTwigView()
    {
        return $this->container->view;
    }

    public function logoutRedirect($response)
    {
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function alreadyExpiredMessage()
    {
        $this->getFlash()->addMessage("info", "Your session is already expired.");
    }

    public function authenticated()
    {
        $this->getTwigView()->getEnvironment()->addGlobal('auth_user', [
            'check'  => Auth::check(),
            'get' => Auth::user()
        ]);
    }

    public function __invoke($request, $response, $next)
    {
        if (!Auth::check())
        {
            goto logout;
        }
        elseif (Auth::isExpired())
        {
            $this->alreadyExpiredMessage();
            goto logout;
        }
        else
        {
            $this->authenticated();
            return $next($request, $response);
        }

        logout:
        Auth::logout();
        return $this->logoutRedirect($response);
    }
}