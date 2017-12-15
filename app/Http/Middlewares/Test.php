<?php

namespace App\Http\Middlewares;

use Middlewares\Middleware;

class Test extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		echo "I am middleware <br>";

		return $next($request, $response);
	}
}
