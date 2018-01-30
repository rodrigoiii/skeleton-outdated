<?php

// \Slim\App
(new RodrigoIII\FrameworkCore\Core)->boot([
    'settings' => [
        'displayErrorDetails' => config('framework.debug'),

        'addContentLengthHeader' => false, // if true = Unexpected data in output buffer

        'determineRouteBeforeAppMiddleware' => config('framework.route_on'),

        'db' => config('framework.database_connection.mysql'),
        'monolog' => config('framework.monolog'),
        'tracy' => config('framework.tracy_debugbar')
    ]
]);