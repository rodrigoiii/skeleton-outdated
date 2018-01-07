<?php

if (is_prod())
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
						config('app.error-pages-path.500')
					);
		};
	};
}

# override not found handler
$container['notFoundHandler'] = function($c)
{
	return function ($request, $response) use($c)
	{
		return $c->twigView
				->render(
					$response->withStatus(404)
					->withHeader('Content-Type', "text/html"),
					config('app.error-pages-path.404')
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
					config('app.error-pages-path.405')
				);
	};
};

# slim twig view
$container['twigView'] = function($c)
{
	$twigView = new \Slim\Views\Twig(resources_path("views"), ['cache' => config('app.cache')]);

	$twigView->addExtension(new \Slim\Views\TwigExtension(
		$c->router,
		$c->request->getUri()
	));

	// tracy debug bar settings
	$twigView->addExtension(new \Twig_Extension_Profiler($c['twig_profile']));
	$twigView->addExtension(new \Twig_Extension_Debug());

	# Make the helper functions as global
	$twigView->getEnvironment()->addGlobal('fn', new Functions);

	# Make 'flash' Global
	$twigView->getEnvironment()->addGlobal('flash', $c->flash);

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
		if (is_prod())
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

# twig profiler
$container['twig_profile'] = function ($c) {
    return new Twig_Profiler_Profile();
};

# monolog logger
$container['logger'] = function($c)
{
	$settings = $c['settings']['monolog'];
	$log = new \Monolog\Logger($settings['name']);
	$log->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
	return $log;
};

# respect validation
$container['validator'] = function($c)
{
	return new \App\Validation\Validator;
};