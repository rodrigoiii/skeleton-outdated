<?php

$app->get('/', "WelcomeController:index");

(new \AuthSlim\AuthRoute(config('auth')))->routes($app, $container);

$app->get('/authenticated-page', function ($request, $response) {
    return $this->view->render($response, "auth/authenticated-home-page.twig");
})
->add(new App\Http\Middlewares\Auth\UserMiddleware($container))
->setName('auth.authenticated-home-page');
