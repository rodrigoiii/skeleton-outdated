<?php

$app = new Slim\App([
	'settings' => [
		'displayErrorDetails' => true // development mode
	]
]);

$container = $app->getContainer();

// php view
$container['phpView'] = function ($c)
{
	return new Slim\Views\PhpRenderer(__DIR__ . "/../resources/views");
};

$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection([
	'driver'    => 'mysql',
	'host'      => 'localhost',
	'database'  => 'test',
	'username'  => 'root',
	'password'  => 'secret123',
	'charset'   => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['TestController'] = function ($c)
{
	return new App\Http\Controllers\TestController($c);
};

require __DIR__ . "/../routes/web.php";

$app->run();