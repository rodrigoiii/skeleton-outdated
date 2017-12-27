<?php

# Global Csrf middleware
$app->add(new \Middlewares\GlobalCsrf($container));
$app->add($container->get('csrf'));

# tracy debugbar
$app->add(new RunTracy\Middlewares\TracyMiddleware($app));

# SharedServer middleware
$app->add(new \Middlewares\SharedServer($container));

# RemoveTrailingSlash middleware
$app->add(new \Middlewares\RemoveTrailingSlash($container));