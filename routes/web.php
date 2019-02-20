<?php

use SkeletonAuthApp\Auth;

/**
 * Register your web routes on this file.
 */
$app->get('/', ["WelcomeController", "index"]);
(new SkeletonAuthApp\Auth($app))->routes();

$app->get('/chat', ["SkeletonChatApp\\ChatController", "index"])
->add("SkeletonAuthApp\\UserMiddleware")
->setName('sklt-chat');
