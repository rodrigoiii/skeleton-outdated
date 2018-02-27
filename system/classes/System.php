<?php

namespace Framework;

class System
{
    public static function init()
    {
        global $app, $container;

        session_start();

        date_default_timezone_set(config('app.default_timezone'));

        require system_path("environment.php");
    }

    public static function process()
    {
        global $app, $container;

        require system_path("system.php");

        require base_path("routes/web.php");
    }
}