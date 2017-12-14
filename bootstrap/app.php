<?php

/*
 |-----------------------------
 | Setup for 'DotEnv'
 |-----------------------------
 */
$dotenv = new \Dotenv\Dotenv(__DIR__ . "/../");
$dotenv->overload();
$dotenv->required([
	# require app configuration
	'APP_NAME', 'APP_URL', 'APP_ENV', 'APP_NAMESPACE', 'APP_KEY',

	# require database configuration
	'DB_HOSTNAME', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE',

	# require development mode
	'DEBUG'
]);

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
		'logger' => config('logger.monolog'),
		'jwt' => config('jwt.settings'),
		'tracy' => config('tracy')
	]
]);
$container = $app->getContainer();

if (is_dev())
{
	# override error handler
	$container['errorHandler'] = function($c)
	{
		return function ($request, $response, $exception) use($c)
		{
			$c['logger']->error($exception);

			return $c->twigView
					->render(
						$response->withStatus(500)
						->withHeader('Content-Type', "text/html"),
						"templates/error-pages/page-500.twig"
					);
		};
	};

	# override not found handler
	$container['notFoundHandler'] = function($c)
	{

		return function ($request, $response) use($c)
		{
			return $c->twigView
					->render(
						$response->withStatus(404)
						->withHeader('Content-Type', "text/html"),
						"templates/error-pages/page-404.twig"
					);
		};
	};

	# override not allowed handler
	$container['notAllowedHandler'] = function($c)
	{

		return function ($request, $response, $methods) use($c)
		{
			return $c->twigView
					->render(
						$response->withStatus(405)
						->withHeader('Allow', implode(', ', $methods))
						->withHeader('Content-Type', "text/html"),
						"templates/error-pages/page-405.twig"
					);
		};
	};
}

# slim twig view
$container['twigView'] = function($c)
{
	$twigView = new \Slim\Views\Twig(__DIR__ . "/../resources/" . (is_prod() ? "dist-" : "") . "views", ['cache' => is_prod()]);

	$twigView->addExtension(new \Slim\Views\TwigExtension(
		$c->router,
		$c->request->getUri()
	));
	$twigView->addExtension(new \Twig_Extension_Profiler($c['twig_profile']));
	$twigView->addExtension(new \Twig_Extension_Debug());

	# Make 'flash' Global
	$twigView->getEnvironment()->addGlobal('flash', $c->flash);

	# Make the helper functions as global
	$twigView->getEnvironment()->addGlobal('fn', new Functions);

	return $twigView;
};

# slim/flash
$container['flash'] = function($c)
{
	return new \Slim\Flash\Messages();
};

# slim/csrf
$container['csrf'] = function($c)
{
	$guard = new \Slim\Csrf\Guard;
	$guard->setFailureCallable( function ($request, $response, $next) use($c) {
		if ( is_prod() )
		{
			return $c->twigView->render(
				$response->withStatus(403)
						->withHeader('Content-Type', "text/html"),
				"templates/error-pages/page-403.twig"
			);
		}

		return $response->withStatus(403)
						->withHeader('Content-Type', "text/html")
						->write("Failed CSRF check!");
	});
	return $guard;
};

# monolog logger
$container['logger'] = function($c)
{
	$settings = $c['settings']['logger'];

	$log = new \Monolog\Logger($settings['name']);
	$log->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

	return $log;
};

# respect validation
$container['validator'] = function($c)
{
	return new \App\Validation\Validator;
};

# gregwar\captcha
$container['captcha'] = function($c)
{
	$builder = new \Gregwar\Captcha\CaptchaBuilder;
	return $builder->build();
};

# nesbot\carbon
$container['carbon'] = function($c)
{
	return new \Carbon\Carbon;
};

# firebase jwt
$container['jwt'] = function($c)
{
	return new \Firebase\JWT\JWT;
};

# controller registered
include __DIR__ . "/registered-controllers.php";

# middleware registered
include __DIR__ . "/registered-global-middlewares.php";

/*
 |-----------------------------
 | Setup for 'Respect Validation'
 |-----------------------------
 */
use Respect\Validation\Validator as v;
v::with('App\\Validation\\Rules\\');

/*
 |-----------------------------
 | Setup for 'Illuminate Database'
 |-----------------------------
 */
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$capsule::connection()->enableQueryLog();

/*
 |-----------------------------
 | Setup for 'Tracy debugger'
 |-----------------------------
 */
use Tracy\Debugger;
if (strpos($container->request->getUri()->getPath(), '/api') !== 0)
{
	Debugger::enable(is_dev() ? Debugger::DEVELOPMENT : Debugger::PRODUCTION, storage_path("logs"));
	Debugger::timer();
}
$container['twig_profile'] = function ($c) {
    return new Twig_Profiler_Profile();
};

# api routes
require __DIR__ . "/../routes/api.php";

# web routes
require __DIR__ . "/../routes/web.php";

# lets rock and roll
$app->run();
