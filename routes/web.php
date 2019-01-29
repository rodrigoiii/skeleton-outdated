<?php

/**
 * Register your web routes on this file.
 */
$app->get('/', ["WelcomeController", "index"]);
(new App\SkeletonAuth\Auth($app))->routes();
