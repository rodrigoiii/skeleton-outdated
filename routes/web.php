<?php

/**
 * Register your web routes on this file.
 */

$app->get('/', ["WelcomeController", "index"]);

$app->group('/auth', function() {
    $this->get('/login', ["LoginController", "getLogin"])->setName('auth.login');
    $this->post('/login', ["LoginController", "postLogin"]);
    $this->post('/logout', ["LoginController", "logout"])->setName('auth.logout');

    $this->post('/register/verify', ["RegisterController", "verify"]);

    // reset password /reset-password?token=
    $this->get('/register/reset-password', ["ResetPasswordController", "getResetPassword"]);
    $this->post('/register/reset-password', ["ResetPasswordController", "postResetPassword"]);
    $this->get('/change-password', ["ChangePasswordController", "getChangePassword"])->setName('auth.change-password');
    $this->post('/change-password', ["ChangePasswordController", "postChangePassword"]);

    $this->get('/forgot-password', ["ForgotPasswordController", "getForgotPassword"])->setName('auth.forgot-password');
    $this->post('/forgot-password', ["ForgotPasswordController", "postForgotPassword"]);

    $this->get('/register', ["RegisterController", "getRegister"])->setName('auth.register');
    $this->post('/register', ["RegisterController", "postRegister"]);
});
