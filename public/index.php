<?php

session_start();

// composer autoload
require __DIR__ . "/../vendor/autoload.php";

date_default_timezone_set(config('app.default_timezone'));

// our application
require __DIR__ . "/../bootstrap/app.php";