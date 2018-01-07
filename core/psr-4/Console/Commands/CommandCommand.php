<?php

namespace Console\Commands;

class CommandCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "make:command {_command}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Create command class template.";

    /**
     * Create a new command instance
     */
    public function __construct()
    {
        $this->namespace = config("app.namespace");

        parent::__construct($this->signature, $this->description);
    }

    /**
     * Execute the console command
     */
    public function handle($input, $output)
    {
        $command = $input->getArgument('_command');

        try {
            if (!ctype_upper($command[0]))
                throw new \Exception("Error: Invalid Command. It must be Characters and PascalCase.", 1);

            if (file_exists(app_path("Console/Commands/{$command}Command.php")))
                throw new \Exception("Error: The Command is already created.", 1);

            $output->writeln($this->makeTemplate($command) ?
                            "Successfully created." . PHP_EOL . "Do not forget to registered it in 'command' file at the root." :
                            "File not created. Check the file path." . PHP_EOL);
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    /**
     * [Create command template]
     * @depends handle
     * @param  [string] $command [command name]
     * @return [boolean]    [Return true if successfully creating file otherwise false]
     */
    private function makeTemplate($command)
    {
        $file = core_path("psr-4/Console/Commands/templates/command.php.dist");

        try {
            if (!file_exists($file)) throw new \Exception("{$file} file is not exist.", 1);

            $template = strtr(file_get_contents($file), [
                '{{namespace}}' => "{$this->namespace}",
                '{{command}}' => $command,
                '{{command_name}}' => strtolower($command)
            ]);

            $file_path = app_path("Console/Commands/{$command}Command.php");

            $file = fopen($file_path, "w");
            fwrite($file, $template);
            fclose($file);

            return file_exists($file_path);

        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }

        return false;
    }
}
