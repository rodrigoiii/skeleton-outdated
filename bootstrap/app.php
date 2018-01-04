<?php

# dotEnv settings
include core_path("settings/dotEnv.php");

/*
 |-----------------------------
 | Setup for 'Slim'
 |-----------------------------
 */
$app = new \Slim\App([
	'settings' => [
		'displayErrorDetails' => config('app.debug'),
		'debug' => config('app.debug'),

		'determineRouteBeforeAppMiddleware' => config('app.route_on'),
		'db' => config('database.connections.mysql'),
		'monolog' => config('monolog'),
		'tracy' => config('tracy')
	]
]);
$container = $app->getContainer();

# container
include core_path("settings/container.php");
# include your other container here ...
# .
# .
# .
# end

# controller registered
include core_path("settings/registered-controllers.php");

# middleware registered
include core_path("settings/registered-global-middlewares.php");
# include your custom middleware as global here ...
# .
# .
# .
# end

# include your custom settings here ...
# .
# .
# .
# end

# web routes
require __DIR__ . "/../routes/web.php";

# lets rock and roll
$app->run();
