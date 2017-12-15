<?php

$app->get('/', function ()
{
	echo "Hello World";
});

$app->get('/test-controller', "TestController:index");

$app->get('/test-view', "TestController:testView");

$app->get('/test-model', "TestController:testModel");

$app->get('/test-middleware', "TestController:testMiddleware")
->add(new App\Http\Middlewares\Test($container));

$app->get('/test-session', "TestController:testSession");