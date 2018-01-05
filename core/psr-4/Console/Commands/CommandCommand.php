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

        if (!ctype_upper($command[0]))
        {
            $output->writeln("Error: Invalid Command. It must be PascalCase.");
        }
        elseif (file_exists(app_path("Console/Commands/{$command}Command.php")))
        {
            $output->writeln("Error: The Command is already created.");
        }
        elseif ($this->makeTemplate($command))
        {
            $output->writeln([
                "Successfully created.",
                "Do not forget to registered it in 'command' file at the root."
            ]);
        }
        else
        {
            $output->writeln("File not created. Check the file path.");
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
        if (file_exists($file))
        {
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
        }
        else
        {
            exit("{{$file}} file is not exist.");
        }

        return false;
    }
}
