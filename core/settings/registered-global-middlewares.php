<?php

# old input middleware
$app->add(new \Middlewares\OldInput($container));

# Global Csrf middleware
$app->add(new \Middlewares\GlobalCsrf($container));
$app->add($container->get('csrf'));

# global error middleware
$app->add(new \Middlewares\GlobalErrors($container));

# tracy debugbar middleware
$app->add(new RunTracy\Middlewares\TracyMiddleware($app));

# SharedServer middleware
$app->add(new \Middlewares\SharedServer($container));

# RemoveTrailingSlash middleware
$app->add(new \Middlewares\RemoveTrailingSlash($container));