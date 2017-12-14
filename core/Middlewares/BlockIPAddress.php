<?php

namespace Middlewares;

use App\Http\Middlewares\Middleware;
use Slim\Exception\NotFoundException;

class BlockIPAddress extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		$file = fopen(security_path('block-ip'), "r");
		$ip_list = [];
		while (! feof($file)) {
			$ip_list[] = trim(fgets($file));
		}
		fclose($file);

		$is_trusted = true;
		foreach ($ip_list as $ip) {
			if ($ip === getUserIP())
			{
				$is_trusted = false;
				break;
			}
		}

		if (!$is_trusted)
		{
			throw new NotFoundException($request, $response);
		}

		return $next($request, $response);
	}
}