<?php

# dotEnv settings
include core_path("settings/dotEnv.php");

/*
 |-----------------------------
 | Setup for 'Slim'
 |-----------------------------
 */
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => config('framework.debug'),

        'addContentLengthHeader' => false, // if true = Unexpected data in output buffer

        'determineRouteBeforeAppMiddleware' => config('framework.route_on'),

        'db' => config('framework.database_connection.mysql'),
        'monolog' => config('framework.monolog'),
        'tracy' => config('framework.tracy_debugbar')
    ]
]);
$container = $app->getContainer();

include core_path('settings/lib.php');
/**
 * include your custom settings here ...
 */


# container
include core_path("settings/container.php");
/**
 * include your other container here ...
 */


# controller registered
include core_path("settings/registered-controllers.php");

# middleware registered
include core_path("settings/registered-global-middlewares.php");
/**
 * include your custom middleware as global here ...
 */


# web routes
require __DIR__ . "/../routes/web.php";

# lets rock and roll
$app->run();