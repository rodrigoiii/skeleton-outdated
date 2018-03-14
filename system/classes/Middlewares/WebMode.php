<?php

namespace Framework\Middlewares;

use Framework\BaseMiddleware;

class WebMode extends BaseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $uri = $request->getUri()->getPath();
        $mode = config("app.web_mode");

        if ($mode !== "UP")
        {
            return $this->twigView
                    ->render(
                        $response->withStatus(200)
                        ->withHeader('Content-Type', "text/html"),
                        "templates/error-pages/under-construction.twig"
                    );
        }

        return $next($request, $response);
    }
}