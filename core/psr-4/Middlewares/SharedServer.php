<?php

namespace Middlewares;

use Slim\Exception\NotFoundException;

class SharedServer extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		if (isSharedServer())
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