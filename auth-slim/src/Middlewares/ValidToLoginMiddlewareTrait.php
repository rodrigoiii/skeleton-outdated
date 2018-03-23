<?php

namespace AuthSlim\Middlewares;

use AuthSlim\Models\AuthAttempt;

trait ValidToLoginMiddlewareTrait
{
    public function lockTemplate($response)
    {
        return $response->write("Login is lock because your login attempts exceed in system limit.");
    }

    public function __invoke($request, $response, $next)
    {
        if (AuthAttempt::isAttemptOver())
        {
            if (!AuthAttempt::isValidToLogin())
            {
                return $this->lockTemplate($response);
            }

            AuthAttempt::reset();
        }

        return $next($request, $response);
    }
}