<?php

namespace App\Console\Commands;

use FrameworkCore\BaseCommand;

class HelloCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "greet:hello";

    /**
     * Console command description
     * @var string
     */
    private $description = "Display Hello World.";

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
        $output->writeln("Hello World");
    }
}
