<?php

namespace Framework\Middlewares;

use Framework\BaseMiddleware;
use Framework\Utilities\Session;

class GlobalErrors extends BaseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        # Make 'validator error' Global
        $this->twigView->getEnvironment()->addGlobal('errors', Session::get('errors', true));

        return $next($request, $response);
    }
}
