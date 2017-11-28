<?php

$app->get('/', function ()
{
	echo "Hello World";
});

$app->get('/test-controller', "TestController:index");

$app->get('/test-view', "TestController:testView");