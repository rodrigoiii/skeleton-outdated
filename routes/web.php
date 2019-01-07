<?php

/**
 * Register your web routes on this file.
 */
$app->get('/', ["WelcomeController", "index"]);
(new App\SkeletonAuthApp\Auth\Auth($app))->routes();
