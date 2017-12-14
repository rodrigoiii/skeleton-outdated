<?php

# tracy debugbar
$app->add(new RunTracy\Middlewares\TracyMiddleware($app));

# csrf field middleware
$app->add(new \Middlewares\GlobalCsrf($container));
$app->add($container->get('csrf'));

# PostRequest
$app->add(new App\Http\Middlewares\PostRequest($container));

# old input middleware
$app->add(new \Middlewares\OldInput($container));

# validator error middleware
$app->add(new \Middlewares\GlobalErrors($container));

# up down site middleware
$app->add(new \Middlewares\DownSite($container));

# remove trailing slash middleware
$app->add(new \Middlewares\RemoveTrailingSlash($container));

# SharedServer middleware
$app->add(new \Middlewares\SharedServer($container));

# Paginator middleware
$app->add(new \Middlewares\Pagination($container));

# Block IP middleware
$app->add(new \Middlewares\BlockIPAddress($container));

