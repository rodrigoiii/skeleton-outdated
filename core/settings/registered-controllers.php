<?php

# WelcomeController
$container['WelcomeController'] = function ($c)
{
	return new App\Http\Controllers\WelcomeController($c);
};


# TestController
$container['TestController'] = function ($c)
{
	return new App\Http\Controllers\TestController($c);
};

