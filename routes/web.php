<?php

/**
 * Register your web routes on this file.
 */

$app->get('/', ["WelcomeController", "index"]);

$app->group('/auth', function() {
    $this->get('/login', ["LoginController", "getLogin"])->setName('auth.login');
    $this->post('/login', ["LoginController", "postLogin"]);
});
