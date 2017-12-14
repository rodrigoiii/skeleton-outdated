<?php

namespace Console\Commands;

class ChangeWebModeCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "web-mode {mode : [UP | DOWN]}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Change web mode either up your application or make it under maintenance.";

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
        $new_mode = strtoupper($input->getArgument('mode'));
        if (!in_array($new_mode, ["UP", "DOWN"])) exit("Invalid web mode. Value must be \"UP\" or \"DOWN\"\n");

        $path = base_path('.env');

        if (file_exists($path))
        {
            $old_mode = _env('WEB_MODE');

            if ($new_mode === $old_mode)
            {
                exit("Web mode is already {$new_mode}.\n");
            }

            file_put_contents($path, str_replace("WEB_MODE={$old_mode}", "WEB_MODE={$new_mode}", file_get_contents($path)));
            $output->writeln("WEB_MODE is now {$new_mode}");
        }
        else
        {
            $output->writeln(".env file is not exist.");
        }
    }
}