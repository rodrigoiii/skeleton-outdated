<?php

use Middlewares as M;

# whoops middleware
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware($app));

# old input middleware
$app->add(new M\OldInput($container));

# Global Csrf middleware
$app->add(new M\GlobalCsrf($container));
$app->add($container->get('csrf'));

# global error middleware
$app->add(new M\GlobalErrors($container));

# SharedServer middleware
$app->add(new M\SharedServer($container));

# RemoveTrailingSlash middleware
$app->add(new M\RemoveTrailingSlash($container));