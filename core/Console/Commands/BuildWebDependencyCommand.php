<?php

namespace Console\Commands;

class BuildWebDependencyCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "dependency:build {action}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Build Dependency.";

    /**
     * Create a new command instance
     */
    public function __construct()
    {
        parent::__construct($this->signature, $this->description);
    }

    /**
     * Execute the console command
     */
    public function handle($input, $output)
    {
        $action = $input->getArgument('action');

        switch ($action) {
            case 'up':
                $commands = [
                    "gulp build",
                    "mkdir resources" . (OS::isWindows() ? "\\" : "/") . "dist-views",
                    "mv public/dist/* resources/dist-views", // move all to resources/dist-views
                    "mv resources/dist-views/dist/* public/dist", // move css and js to public/dist
                    "rm -r resources/dist-views/dist", // remove dist folder in resources/dist-views folder
                    "mv resources/dist-views/fonts public/dist", // move fonts to public/dist
                    "mv resources/dist-views/img public/dist", // move img to public/dist
                    "mv public/dist/img/img/* public/dist/img", // flatten the img inside of dist
                    "rm -r public/dist/img/img", // remove excess img directory
                    "php command app-environment production", // change APP_ENV to production
                ];

                $output->writeln("This will take a long time. Please wait until the progress finish.\n Loading . . .");
                $output->writeln(shell_exec(implode(" && ", $commands)));
                break;

            case 'down':
                $commands = [
                    "gulp delete-dist", // delete dist folder
                    "rm -r resources/dist-views", // delete dist-views folder
                    "php command app-environment local", // change APP_ENV to local
                ];

                $output->writeln(shell_exec(implode(" && ", $commands)));
                break;

            case 'refresh':
                $commands = [
                    "php command dependency:build down",
                    "php command dependency:build up",
                ];

                $output->writeln(shell_exec(implode(" && ", $commands)));
                break;

            default:
                $output->writeln("Error: Invalid action. It must be 'up' or 'down'");
        }

        exit;
    }
}