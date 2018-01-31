<?php

namespace Framework\Auth;

use Framework\Auth\Bridge;

class Route
{
    public function __construct()
    {
        global $app, $container;
        static::setupRoutes($app, $container);
    }

    public static function setupRoutes($app, $container)
    {
        $url_prefix = Bridge::config('url_prefix');

        $ValidToLogin = Bridge::middleware("ValidToLogin");
        $User = Bridge::middleware("User");
        $Guest = Bridge::middleware("Guest");

        $app->group("/{$url_prefix}", function() use ($container, $ValidToLogin, $User, $Guest)
        {
            $this->group('/login', function () use ($container)
            {
                # get login
                $this->get('', Bridge::controller('AuthController') . ":getLogin")->setName('auth.login');

                // # post login
                $this->post('', Bridge::controller('AuthController') . ":postLogin");
            })
            ->add(new $ValidToLogin($container))
            ->add(new $Guest($container));

            # logout
            $this->post('/logout', Bridge::controller('AuthController') . ":logout")
            ->add(new $User($container))
            ->setName('auth.logout');
        });

        $app->get("/{$url_prefix}/home", function ($request, $response)
        {
            return $this->twigView->render($response, "_auth/home.twig");
        })->setName('auth.home')
        ->add(new $User($container));
    }
}