<?php

namespace Framework;

use Framework\Console\Commands;
use Symfony\Component\Console\Application;

# settings path
define('SP', __DIR__ . "/../settings");

final class CoreCommand
{
    /**
     * Boot all need of application.
     * @return void
     */
    public function boot(array $custom_commands)
    {
        if (PHP_SAPI !== "cli") die; // die if not using cli

        $app = new Application(config('app.name'));

        $app->addCommands([
            new Commands\CommandCommand,
            new Commands\ControllerMakeCommand,
            new Commands\ControllerRemoveCommand,
            new Commands\GenerateKeyCommand,
            new Commands\MiddlewareCommand,
            new Commands\ModelCommand,
            new Commands\RuleCommand,
            new Commands\RequestCommand,
            new Commands\ChangeWebModeCommand,
            new Commands\ChangeEnvironmentCommand,

            // modules
            new Commands\_AuthCommand,
            # use rodrigoiii/notification-slim library to enable this feature
            // new Commands\_NotificationCommand,
        ]);

        $app->addCommands($custom_commands);

        // run the application
        $app->run();
    }
}