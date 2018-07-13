<?php

/**
 * Register your web routes on this file.
 */

$app->get('/', ["WelcomeController", "index"]);

$app->group('/users', function () {
    $this->get('', ["UserController", "index"])->setName('user.list');
    $this->get('/data', ["UserController", "data"])->setName('api.user.data');

    $this->get('/{id:[0-9]+}/show', ["UserController", "show"])->setName('user.show');
    $this->get('/search', ["UserController", "search"])->setName('user.search');

    $this->get('/create', ["UserController", "create"])->setName('user.create');
    $this->post('/create', ["UserController", "store"]);

    $this->get('/{id:[0-9]+}/edit', ["UserController", "edit"])->setName('user.edit');
    $this->put('/{id:[0-9]+}/edit', ["UserController", "update"]);

    $this->delete('/{id:[0-9]+}/delete', ["UserController", "delete"])->setName('user.delete');
});
