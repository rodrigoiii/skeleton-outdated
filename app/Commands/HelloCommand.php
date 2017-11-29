<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class HelloCommand extends Command
{
	protected function configure ()
	{
		$this->setName('hello')
			->setDescription("Greet command, just for guide to create your custom command.");
	}

	protected function execute (InputInterface $input, OutputInterface $output)
	{
		$output->writeln("Hello WOrld");
	}
}
