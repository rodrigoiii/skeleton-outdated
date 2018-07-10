<?php

$app = new FrameworkCore\SlimRodrigo([
    'settings.displayErrorDetails' => config('app.debug'),
    'settings.addContentLengthHeader' => false, // if true = Unexpected data in output buffer
    'settings.determineRouteBeforeAppMiddleware' => config('app.route_on'),

    'settings.db' => config('database.database_connection.mysql'),
    'settings.tracy' => config('debug-bar.tracy.settings')
]);

# Application Container
$container = $app->getContainer();

$app->boot();

return $app;
