<?php

/**
 * Register your web routes on this file.
 */

$app->get('/', ["WelcomeController", "index"]);

$app->group('/auth', function() {
    $this->get('/login', ["LoginController", "getLogin"])->setName('auth.login');
    $this->post('/login', ["LoginController", "postLogin"]);

    // registration /register/verify?token=
    $this->post('/register/verify', ["RegisterController", "verify"]);

    // reset password /reset-password?token=
    $this->get('/register/reset-password', ["ResetPasswordController", "getResetPassword"]);
    $this->post('/register/reset-password', ["ResetPasswordController", "postResetPassword"]);
});
