<?php

namespace Framework;

class ConsoleSystem
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

        require system_path("environment.php");
    }
}