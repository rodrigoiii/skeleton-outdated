<?php

namespace FrameworkCore\Middlewares;

use FrameworkCore\BaseMiddleware;
use Slim\Exception\NotFoundException;

class AjaxRequest extends BaseMiddleware
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