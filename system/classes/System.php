<?php

namespace Framework;

class System
{
    public static function init()
    {
        global $app, $container;

        # start the session
        session_start();

        # change default timezone
        date_default_timezone_set(config('app.default_timezone'));

        # setup class alias
        foreach (config('app.aliases') as $alias => $class) {
            class_alias($class, $alias);
        }

        require system_path("environment.php");
    }

    public static function process()
    {
        global $app, $container;

        require system_path("system.php");

        require base_path("routes/web.php");
    }
}