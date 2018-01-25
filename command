#!/usr/bin/env php
<?php

if (PHP_SAPI !== "cli") die; // die if not using cli

require __DIR__ . "/vendor/autoload.php";

include core_path("settings/dotEnv.php");

$app = new Symfony\Component\Console\Application("My Framework");

/**
 * Core Commands
 */
use Console\Commands as C;

$app->addCommands([
    new C\CommandCommand,
    new C\ControllerMakeCommand,
    new C\ControllerRemoveCommand,
    new C\GenerateKeyCommand,
    new C\MiddlewareCommand,
    new C\ModelCommand,
    new C\RuleCommand,
    new C\RequestCommand,
    new C\ChangeWebModeCommand,
    new C\ChangeEnvironmentCommand,
    new C\TestCommand

    # use rodrigoiii/notification library to enable this feature
    // new C\NotificationCommand,
]);

/**
 * Your Custom Commands here
 */
use App\Console\Commands as CC;

$app->addCommands([
    new CC\HelloCommand
]);

$app->run();