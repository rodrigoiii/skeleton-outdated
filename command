#!/usr/bin/env php
<?php

if (PHP_SAPI !== "cli") die; // die if not using cli

# composer autoload
require __DIR__ . "/vendor/autoload.php";

use App\Console\Commands as AppCommand;
use FrameworkCore\Console\Commands as Command;
use Symfony\Component\Console\Application;

// include application
include __DIR__ . "/bootstrap/app.php";

$console_app = new Application(config('app.name'));

$app_commands = [
    new AppCommand\HelloCommand
];

$framework_commands = [
    new Command\MakeCommandCommand,
    new Command\MakeControllerCommand,
    new Command\RemoveControllerCommand,
    new Command\GenerateKeyCommand,
    new Command\MakeMiddlewareCommand,
    new Command\MakeModelCommand,
    new Command\MakeRuleCommand,
    new Command\MakeRequestCommand,
    new Command\ChangeWebModeCommand,
    new Command\ChangeEnvironmentCommand,
    new Command\MakeTestCommand,
    new Command\BuildDistCommand,
    new Command\DeleteDistCommand,

    # auth command
    // new Command\_MakeAuthCommand,

    # quick crud
    // new Command\_MakeCrudCommand,
    // new Command\_RemoveCrudCommand,

    # email notification
    // new Command\_MakeNotificationCommand
];

$console_app->addCommands(array_merge($app_commands, $framework_commands));

# run the application
$console_app->run();