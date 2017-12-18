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
		'displayErrorDetails' => is_dev(),
		'debug' => is_dev(),

		'determineRouteBeforeAppMiddleware' => config('app.route_on'),
		'db' => config('database.connections.mysql'),
		'monolog' => config('monolog'),
		'tracy' => config('tracy')
	]
]);
$container = $app->getContainer();

# include core settings
include core_path("settings/core-settings.php");

# include your other settings here
# .
# .
# .
# end

# web routes
require __DIR__ . "/../routes/web.php";

# lets rock and roll
$app->run();
