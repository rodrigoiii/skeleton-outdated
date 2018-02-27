<?php

namespace Framework;

class System
{
    public static function init()
    {
        session_start();

        date_default_timezone_set(config('app.default_timezone'));

        require __DIR__ . "/../environment.php";
    }

    public static function process()
    {
        require __DIR__ . "/../system.php";

        require __DIR__ . "/../routes/web.php";
    }
}