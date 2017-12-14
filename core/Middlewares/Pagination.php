<?php

namespace Middlewares;

use App\Http\Middlewares\Middleware;
use Illuminate\Pagination\Paginator;

class Pagination extends Middleware
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