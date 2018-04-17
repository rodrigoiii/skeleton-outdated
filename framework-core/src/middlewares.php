<?php

use FrameworkCore\Middlewares as M;

# tracy debugbar
if (!is_prod())
{
    $app->add(new \RunTracy\Middlewares\TracyMiddleware($app));
}

# determine route before app middleware
$app->add(new M\AppMiddleware($container));

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

# RemoveTrailingSlash middleware
$app->add(new M\WebMode($container));