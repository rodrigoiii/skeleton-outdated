<?php

# WelcomeController
$container['WelcomeController'] = function ($c)
{
    return new App\Http\Controllers\WelcomeController($c);
};

# Auth\AuthController
$container['Auth\AuthController'] = function ($c)
{
    return new App\Http\Controllers\Auth\AuthController($c);
};

# Auth\RegisterController
$container['Auth\RegisterController'] = function ($c)
{
    return new App\Http\Controllers\Auth\RegisterController($c);
};

