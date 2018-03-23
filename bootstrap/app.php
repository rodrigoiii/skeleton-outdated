<?php

Framework\System::init();

# Framework Application
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => config('app.debug'),

        'addContentLengthHeader' => false, // if true = Unexpected data in output buffer

        'determineRouteBeforeAppMiddleware' => config('app.route_on'),

        'db' => config('database.database_connection.mysql'),
        'monolog' => config('logger.monolog'),
        'tracy' => config('debug-bar.tracy.settings'),

        'mail' => config('notification-slim')
    ]
]);

# Application Container
$container = $app->getContainer();

Framework\System::process($app, $container);

return $app;