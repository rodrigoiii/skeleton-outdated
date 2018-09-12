<?php

SkeletonCore\App::loadEnvironment();

$app = new SkeletonCore\App([
    'settings.displayErrorDetails' => config('app.debug'),
    'settings.addContentLengthHeader' => false, // disable the automatic addition of the Content-Length header in the response
    'settings.determineRouteBeforeAppMiddleware' => config('app.route_on'),

    'settings.db' => config('database.database_connection.mysql'),
    'settings.tracy' => config('debug-bar.tracy.settings')
]);

$app->boot();

return $app;
