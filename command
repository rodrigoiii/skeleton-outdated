<?php

if (PHP_SAPI !== "cli") die; // die if not using cli

require __DIR__ . "/vendor/autoload.php";

$app = new Symfony\Component\Console\Application;

/**
 * Commands section here
 */
$app->add(new App\Commands\HelloCommand);

$app->run();