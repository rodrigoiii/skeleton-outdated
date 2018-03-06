<?php

namespace Framework\Console\Commands;

use Framework\BaseCommand;

class DeleteDistCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "delete:dist";

    /**
     * Console command description
     * @var string
     */
    private $description = "Purge the build:dist command.";

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
        try {
            if (strtolower(PHP_OS) !== "linux")
            {
                throw new \Exception("Sorry but for now build:dist is supported for linux only.", 1);
            }

            $commands = [
                "if [ -d public/dist ]; then
                    rm -rf public/dist
                fi",
                "if [ -d resources/dist-views ]; then
                    rm -rf resources/dist-views
                fi"
            ];

            $output->writeln("Please wait ...");
            shell_exec(implode(" && ", $commands));
            $output->writeln("Purge dist ok.");
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}
