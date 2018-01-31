<?php

namespace Framework\Console\Commands;

use Framework\BaseCommand;

class ChangeEnvironmentCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "change:environment {environment : [development | production | testing]} {--p|phinx}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Change environment of your application to 'development', 'production' or 'testing'.";

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
        $new_env = strtolower($input->getArgument('environment'));
        $include_phinx = $input->getOption('phinx');

        $env_path = base_path(".env");
        $phinx_path = base_path("phinx.yml");

        try {
            if (!file_exists($env_path))
                throw new \Exception(".env file is not exist.", 1);

            if (!in_array($new_env, ["development", "production", "testing"]))
                throw new \Exception("Invalid environment. Value must be \"development\", \"production\" or \"testing\"", 1);

            $old_env = _env('APP_ENV');
            file_put_contents($env_path, str_replace("APP_ENV={$old_env}", "APP_ENV={$new_env}", file_get_contents($env_path)));
            $output->writeln("APP_ENV is now {$new_env}");

            if ($include_phinx)
            {
                if (!file_exists($phinx_path))
                    throw new \Exception("phinx.yml file is not exist. Try command 'vendor/bin/phinx init' first.", 1);

                file_put_contents($phinx_path, str_replace("default_database: {$old_env}", "default_database: {$new_env}", file_get_contents($phinx_path)));
                $output->writeln("default_database is now {$new_env}");
            }
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}