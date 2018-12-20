<?php

/**
 * Register your web routes on this file.
 */

$app->get('/', ["WelcomeController", "index"]);

$app->group('/auth', function() {
    $this->group('/login', function() {
        $this->get('', ["LoginController", "getLogin"])->setName('auth.login');
        $this->post('', ["LoginController", "postLogin"]);
    })->add(GuestMiddleware::class);

    $this->post('/logout', ["LoginController", "logout"])
        ->setName('auth.logout')
        ->add(UserMiddleware::class);

    $this->group('/register', function() {
        $this->get('', ["RegisterController", "getRegister"])->setName('auth.register');
        $this->post('', ["RegisterController", "postRegister"]);
        $this->get('/verify/{token}', ["RegisterController", "verify"]);
    })->add(GuestMiddleware::class);

    $this->group('/forgot-password', function() {
        $this->get('', ["ForgotPasswordController", "getForgotPassword"])->setName('auth.forgot-password');
        $this->post('', ["ForgotPasswordController", "postForgotPassword"]);
    })->add(GuestMiddleware::class);

    $this->group('/reset-password', function() {
        $this->get('/{token}', ["ResetPasswordController", "getResetPassword"])->setName('auth.reset-password');
        $this->post('/{token}', ["ResetPasswordController", "postResetPassword"]);
    })->add(GuestMiddleware::class);

    $this->group('/change-password', function() {
        $this->get('', ["ChangePasswordController", "getChangePassword"])->setName('auth.change-password');
        $this->post('', ["ChangePasswordController", "postChangePassword"]);
    })->add(UserMiddleware::class);

    $this->get('/home', function() {
        return "home";
    })
    ->setName('auth.home')
    ->add(UserMiddleware::class);
});
