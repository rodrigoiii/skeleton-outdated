<?php

namespace Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use App\Utilities\ConsoleCommandParser;

class BaseCommand extends Command
{
	private $signature;
	private $description;

	private $name;
	private $arguments;
	private $optional;

	public function __construct($signature, $description)
	{
		$this->signature = $signature;
		$this->description = $description;

		list($this->name, $this->arguments, $this->options) = ConsoleCommandParser::parse($this->signature);

		parent::__construct();
	}

	protected function configure ()
	{
		$this->setName($this->name);
		$this->setDescription($this->description);

		foreach ($this->arguments as $argument) {
		    $this->getDefinition()->addArgument($argument);
		}

		foreach ($this->options as $option) {
		    $this->getDefinition()->addOption($option);
		}
	}

	protected function execute (InputInterface $input, OutputInterface $output)
	{
		$this->handle($input, $output);
	}
}
