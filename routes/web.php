<?php

/**
 * Register your web routes on this file.
 */
$app->get('/', ["WelcomeController", "index"]);
(new SkeletonAuthApp\Auth($app))->routes();
