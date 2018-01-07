<?php

namespace Console\Commands;

class GenerateKeyCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "key:generate";

    /**
     * Console command description
     * @var string
     */
    private $description = "Generate new hash key for APP_KEY environment.";

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
        $path = base_path('.env');

        try {
            if (!file_exists($path))
                throw new \Exception(".env file is not exist.", 1);

            $old_key = config('app.key');
            $new_key = sha1(uniqid());

            file_put_contents($path, str_replace("APP_KEY={$old_key}", "APP_KEY={$new_key}", file_get_contents($path)));
            $output->writeln("APP_KEY is now {$new_key}");
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}