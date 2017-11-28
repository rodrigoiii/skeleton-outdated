<?php

$app = new Slim\App([
	'settings' => [
		'displayErrorDetails' => true // development mode
	]
]);

$container = $app->getContainer();

$container['TestController'] = function ($c)
{
	return new App\Http\Controllers\TestController($c);
};

require __DIR__ . "/../routes/web.php";

$app->run();