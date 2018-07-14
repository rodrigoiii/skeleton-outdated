<?php

namespace App\Commands;

use SlimRodrigoCore\BaseCommand;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;

class HelloCommand extends BaseCommand
{
    /**
     * The command signature.
     *
     * @var string
     */
    private $signature = "greet:hello";

    /**
     * The command description.
     *
     * @var string
     */
    private $description = "Display Hello World.";

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct($this->signature, $this->description);
    }

    /**
     * To be call after execute the command.
     *
     * @param  Input $input
     * @param  Output $output
     * @return void
     */
    public function handle(Input $input, Output $output)
    {
        $output->writeln("Hello World");
    }
}
