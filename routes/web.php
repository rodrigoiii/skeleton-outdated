<?php

$app->get('/', "WelcomeController:index");

$app->get('/test', "TestController:index")->setName('test');
$app->post('/test', "TestController:index2");