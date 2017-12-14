<?php

namespace Middlewares;

use App\Http\Middlewares\Middleware;
use Slim\Exception\NotFoundException;

class AjaxRequest extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		if (!$request->isXhr())
		{
			$this->logger->error("Request must be ajax.");
			throw new NotFoundException($request, $response);
		}

		return $next($request, $response);
	}
}