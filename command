#!/usr/bin/env php
<?php

if (PHP_SAPI !== "cli") die; // die if not using cli

use App\Console\Commands as AppCommand;
use Framework\ConsoleSystem;
use Framework\Console\Commands as Command;
use Symfony\Component\Console\Application;

# composer autoload
require __DIR__ . "/vendor/autoload.php";

ConsoleSystem::init();

$app = new Application(config('app.name'));

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

    # auth command
    // new Command\_MakeAuthCommand,

    # quick crud
    // new Command\_MakeQuickCrudCommand,
    // new Command\_RemoveQuickCrudCommand,

    # email notification
    // new Command\_MakeNotificationCommand
];

$app->addCommands(array_merge($app_commands, $framework_commands));

# run the application
$app->run();