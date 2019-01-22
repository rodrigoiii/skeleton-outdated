<?php
ini_set("display_errors", 1);
SkeletonCore\App::loadEnvironment();

$app = new SkeletonCore\App([
    'settings.displayErrorDetails' => config('app.debug'),
    'settings.addContentLengthHeader' => false, // disable the automatic addition of the Content-Length header in the response
    'settings.determineRouteBeforeAppMiddleware' => config('app.route_on'),

    'settings.db' => config('database')
]);

$app->run();
