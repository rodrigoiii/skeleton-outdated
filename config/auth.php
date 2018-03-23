<?php

return [
    'AuthController' => App\Http\Controllers\Auth\AuthController::class,
    'RegisterController' => App\Http\Controllers\Auth\RegisterController::class,
    'ChangePasswordController' => App\Http\Controllers\Auth\ChangePasswordController::class,
    'ForgotPasswordController' => App\Http\Controllers\Auth\ForgotPasswordController::class,
    'ResetPasswordController' => App\Http\Controllers\Auth\ResetPasswordController::class,

    'ValidToLoginMiddleware' => App\Http\Middlewares\Auth\ValidToLoginMiddleware::class,
    'UserMiddleware' => App\Http\Middlewares\Auth\UserMiddleware::class,
    'GuestMiddleware' => App\Http\Middlewares\Auth\GuestMiddleware::class
];