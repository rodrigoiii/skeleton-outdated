<?php

namespace Framework;

use Framework\Middlewares as M;

# settings path
define('SP', __DIR__ . "/../settings");

final class Core
{
    /**
     * Boot all need of application.
     * @return void
     */
    public function boot()
    {
        global $app, $container;

        $this->includeSettings($app, $container);

        // the registered routes
        require routes_path("web.php");

        // run the application
        $app->run();
    }

    /**
     * @depends boot
     * @return void
     */
    private function includeSettings($app, $container)
    {
        include SP . "/lib.php";
        include SP . "/container.php";

        # register controllers
        include settings_path("registered-controllers.php");

        # tracy debugbar
        if (!is_prod())
        {
            $app->add(new \RunTracy\Middlewares\TracyMiddleware($app));
        }

        # old input middleware
        $app->add(new M\OldInput($container));

        # Global Csrf middleware
        $app->add(new M\GlobalCsrf($container));
        $app->add($container->get('csrf'));

        # global error middleware
        $app->add(new M\GlobalErrors($container));

        # SharedServer middleware
        $app->add(new M\SharedServer($container));

        # RemoveTrailingSlash middleware
        $app->add(new M\RemoveTrailingSlash($container));

        # custom registered middlewares
        include settings_path("registered-global-middlewares.php");
    }
}