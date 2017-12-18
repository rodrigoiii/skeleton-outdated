<?php

# tracy debugbar
$app->add(new RunTracy\Middlewares\TracyMiddleware($app));

# SharedServer middleware
$app->add(new \Middlewares\SharedServer($container));

# RemoveTrailingSlash middleware
$app->add(new \Middlewares\RemoveTrailingSlash($container));

// include global middlewares that developer provide
include settings_path("registered-global-middlewares.php");