<?php

namespace Framework\Middlewares;

use Framework\BaseMiddleware;
use Illuminate\Pagination\Paginator;

class Pagination extends BaseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        if (!is_null($request->getParam('page')))
        {
            Paginator::currentPageResolver(function () use ($request)
            {
                return $request->getParam('page');
            });
        }

        return $next($request, $response);
    }
}