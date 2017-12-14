<?php

namespace Middlewares;
use App\Http\Middlewares\Middleware;
use Slim\Exception\NotFoundException;

class DownSite extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		$uri = $request->getUri()->getPath();
		$mode = _env('WEB_MODE', "DOWN");

		if ($mode !== "UP")
		{
			return $this->twigView
					->render(
						$response->withStatus(404)
						->withHeader('Content-Type', "text/html"),
						"templates/error-pages/under-construction.twig"
					);
		}

		return $next($request, $response);
	}
}
