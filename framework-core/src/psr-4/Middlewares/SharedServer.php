<?php

namespace Framework\Middlewares;

use Framework\BaseMiddleware;
use Slim\Exception\NotFoundException;

class SharedServer extends BaseMiddleware
{
	public function __invoke($request, $response, $next)
	{
		if (is_shared_server())
		{
			$folders = array_map('basename', glob(base_path() . "/*", GLOB_ONLYDIR));
			$url = trim($_SERVER['REQUEST_URI'], "/");
			$explode_url = explode("/", $url);

			if (in_array($explode_url[0], $folders))
			{
				throw new NotFoundException($request, $response);
			}
		}

		return $next($request, $response);
	}
}