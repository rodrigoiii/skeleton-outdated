<?php

namespace Framework\Middlewares;

use Framework\BaseMiddleware;

class GlobalCsrf extends BaseMiddleware
{
	public function __invoke($request, $response, $next)
	{
		# Make 'csrf' Global
		$name_key  = $this->csrf->getTokenNameKey();
		$value_key = $this->csrf->getTokenValueKey();
		$name  = $request->getAttribute($name_key);
		$value = $request->getAttribute($value_key);

		$json_token = json_encode([$name_key => $name, $value_key => $value]);

		$this->view->getEnvironment()->addGlobal('csrf', [
			'field' => '
				<input type="hidden" name="'.$name_key.'" value="'.$name.'">
				<input type="hidden" name="'.$value_key.'" value="'.$value.'">
			',
			'json' => $json_token
		]);

		$response = $response->withAddedHeader('X-CSRF-TOKEN', $json_token);

		return $next($request, $response);
	}
}