<?php

FrameworkCore\System::init();

# Framework Application
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => config('app.debug'),

        'addContentLengthHeader' => false, // if true = Unexpected data in output buffer

        'determineRouteBeforeAppMiddleware' => config('app.route_on'),

        'db' => config('database.database_connection.mysql'),
        'monolog' => config('logger.monolog'),
        'tracy' => config('debug-bar.tracy.settings'),
        'queue-job' => config('queue-job.pheanstalk')
    ]
]);

# Application Container
$container = $app->getContainer();

FrameworkCore\System::process($app, $container);

return $app;