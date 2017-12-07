<?php

if (PHP_SAPI !== "cli") die; // die if not using cli

require __DIR__ . "/vendor/autoload.php";

$app = new Symfony\Component\Console\Application("My Framework 2");

/**
 * Commands section here
 */
$app->add(new App\Console\Commands\HelloCommand);

$app->run();