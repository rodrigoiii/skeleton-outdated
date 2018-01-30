<?php

# Slim\App
(new Framework\Core)->boot([
    'settings' => [
        'displayErrorDetails' => config('app.debug'),

        'addContentLengthHeader' => false, // if true = Unexpected data in output buffer

        'determineRouteBeforeAppMiddleware' => config('app.route_on'),

        'db' => config('database.database_connection.mysql'),
        'monolog' => config('logger.monolog'),
        'tracy' => config('debug-bar.settings')
    ]
]);