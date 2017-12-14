<?php

namespace Middlewares;

use App\Http\Middlewares\Middleware;

class GlobalCsrf extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		# Make 'csrf' Global
		$name_key  = $this->csrf->getTokenNameKey();
		$value_key = $this->csrf->getTokenValueKey();
		$name  = $request->getAttribute($name_key);
		$value = $request->getAttribute($value_key);

		$this->twigView->getEnvironment()->addGlobal('csrf', [
			'field' => '
				<input type="hidden" name="'.$name_key.'" value="'.$name.'" id="csrf-name">
				<input type="hidden" name="'.$value_key.'" value="'.$value.'" id="csrf-value">
			'
		]);

		$response = $response->withAddedHeader('X-CSRF-TOKEN', json_encode([$name_key => $name, $value_key => $value]));

		return $next($request, $response);
	}
}