<?php

$app->get('/', function ()
{
	echo "Hello World <br>";
	echo "Please see the routes/web.php to see all routes registered.";
});

$app->get('/test-controller', "TestController:index");

$app->get('/test-view', "TestController:testView");

$app->get('/test-model', "TestController:testModel");

$app->get('/test-middleware', "TestController:testMiddleware")
->add(new App\Http\Middlewares\Test($container));

$app->get('/test-session', "TestController:testSession");

$app->get('/test-log', "TestController:testLog");

$app->get('/test-ajax', "TestController:testAjax");
$app->get('/test-ajax-get-to-be-call', "TestController:testGetAjaxToBeCall");
$app->post('/test-ajax-post-to-be-call', "TestController:testPostAjaxToBeCall")->setName('test-ajax-post');