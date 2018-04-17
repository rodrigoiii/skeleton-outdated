<?php

namespace FrameworkCore\Middlewares;

use FrameworkCore\BaseMiddleware;

class AppMiddleware extends BaseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $route = $request->getAttribute('route');
        $server = $_SERVER;

        $this->view->getEnvironment()->addGlobal('route', $route);
        $this->view->getEnvironment()->addGlobal('server', $server);

        return $next($request, $response);
    }
}