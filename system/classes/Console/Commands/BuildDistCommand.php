<?php

namespace Framework\Console\Commands;

use Framework\BaseCommand;

class BuildDistCommand extends BaseCommand
{
	/**
	 * Console command signature
	 * @var string
	 */
	private $signature = "build:dist";

	/**
	 * Console command description
	 * @var string
	 */
	private $description = "Build dist folder with minified css,js and optimize images.";

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
				// remove public/dist if exist
				"if [ -d public/dist ]; then
					rm -rf public/dist
				fi",

				// run web-dev-tools build:dist command
				"npx web-dev-tools build:dist",

				"if [ -d resources/dist-views/img ]; then
					mv resources/dist-views/img resources/dist-views/dist
				fi",
				"if [ -d resources/dist-views/fonts ]; then
					mv resources/dist-views/fonts resources/dist-views/dist
				fi",
				"if [ -d resources/dist-views/dist ]; then
					mv resources/dist-views/dist public
				fi"
			];

			$output->writeln("Please wait ...");
			shell_exec(implode(" && ", $commands));
			$output->writeln("Build dist ok.");
		} catch (\Exception $e) {
			$output->writeln($e->getMessage());
		}
	}
}
