<?php

namespace FrameworkCore;

class System
{
    public static function init()
    {
        # start the session
        session_start();

        # change default timezone
        date_default_timezone_set(config('app.default_timezone'));

        # setup class alias
        foreach (config('app.aliases') as $alias => $class) {
            class_alias($class, $alias);
        }
    }

    public static function process($app, $container)
    {
        require __DIR__ . "/../libraries.php";

        require __DIR__ . "/../container.php";

        require __DIR__ . "/../middlewares.php";

        require system_path("registered-controllers.php");

        require system_path("registered-global-middlewares.php");

        require base_path("routes/web.php");
    }
}