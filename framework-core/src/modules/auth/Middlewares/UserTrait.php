<?php

namespace Framework\Auth\Middlewares;

use Framework\Auth\Auth\Auth;
use Framework\Auth\Bridge;

trait UserTrait
{
    public function sessionExpiredMessage()
    {
        $this->flash->addMessage("info", "Your session is already expired.");
    }

    public function setAuthUserData()
    {
        $this->twigView->getEnvironment()->addGlobal('auth_user', [
            'get'  => Auth::user(),
            'check'  => Auth::check(),
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
            $this->sessionExpiredMessage();
            goto logout;
        }
        else
        {
            $this->setAuthUserData();
            return $next($request, $response);
        }

        logout:
        Auth::logout();
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }
}