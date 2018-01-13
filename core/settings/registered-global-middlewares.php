<?php

use Middlewares as M;

# old input middleware
$app->add(new M\OldInput($container));

# Global Csrf middleware
$app->add(new M\GlobalCsrf($container));
$app->add($container->get('csrf'));

# global error middleware
$app->add(new M\GlobalErrors($container));

# tracy debugbar middleware
$app->add(new RunTracy\Middlewares\TracyMiddleware($app));

# SharedServer middleware
$app->add(new M\SharedServer($container));

# RemoveTrailingSlash middleware
$app->add(new M\RemoveTrailingSlash($container));