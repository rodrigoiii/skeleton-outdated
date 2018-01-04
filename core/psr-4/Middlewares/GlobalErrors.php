<?php

namespace Middlewares;

use Session;

class GlobalErrors extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        # Make 'validator error' Global
        $this->twigView->getEnvironment()->addGlobal('errors', Session::get('errors', true));

        return $next($request, $response);
    }
}
