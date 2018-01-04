<?php

if (PHP_SAPI !== "cli") die; // die if not using cli

require __DIR__ . "/vendor/autoload.php";

include core_path("settings/dotEnv.php");

$app = new Symfony\Component\Console\Application("My Framework 2");

# Core Commands
$app->add(new Console\Commands\CommandCommand);
$app->add(new Console\Commands\ControllerCommand);
$app->add(new Console\Commands\GenerateKeyCommand);
$app->add(new Console\Commands\MiddlewareCommand);
$app->add(new Console\Commands\ModelCommand);
$app->add(new Console\Commands\ValidatorCommand);
$app->add(new Console\Commands\TestCommand);

/**
 * Your Custom Commands here
 */
$app->add(new App\Console\Commands\HelloCommand);

$app->run();