<?php

namespace App\Console\Commands;

class HelloCommand extends BaseCommand
{
	private $signature = "greet:hello";

	private $description = "Greet command, just for guide to create your custom command.";

	public function __construct()
	{
		parent::__construct($this->signature, $this->description);
	}

	public function handle($input, $output)
	{
		$output->writeln("Hello WOrld");
	}
}
