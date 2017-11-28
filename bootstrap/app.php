<?php

$app = new Slim\App([
	'settings' => [
		'displayErrorDetails' => true // development mode
	]
]);

require __DIR__ . "/../routes/web.php";

$app->run();