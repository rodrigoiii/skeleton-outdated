<?php

namespace FrameworkCore\Middlewares;

use FrameworkCore\BaseMiddleware;
use FrameworkCore\Utilities\Session;

class GlobalErrors extends BaseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        # Make 'validator error' Global
        $this->view->getEnvironment()->addGlobal('errors', Session::get('errors', true));

        return $next($request, $response);
    }
}
