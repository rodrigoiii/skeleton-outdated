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

# Auth\ChangePasswordController
$container['Auth\ChangePasswordController'] = function ($c)
{
    return new App\Http\Controllers\Auth\ChangePasswordController($c);
};

# Auth\ForgotPasswordController
$container['Auth\ForgotPasswordController'] = function ($c)
{
    return new App\Http\Controllers\Auth\ForgotPasswordController($c);
};

# Auth\ResetPasswordController
$container['Auth\ResetPasswordController'] = function ($c)
{
    return new App\Http\Controllers\Auth\ResetPasswordController($c);
};

