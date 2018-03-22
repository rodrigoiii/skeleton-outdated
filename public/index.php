<?php

// composer autoload
require __DIR__ . "/../vendor/autoload.php";

// application environment
require system_path("environment.php");

// our application
$app = include __DIR__ . "/../bootstrap/app.php";

$app->run();