<?php

namespace Console\Commands;

class UtilityCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "make:utility {utility}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Create utility class template.";

    /**
     * Create a new command instance
     */
    public function __construct()
    {
        $this->namespace = _env('APP_NAMESPACE', "App");

        parent::__construct($this->signature, $this->description);
    }

    /**
     * Execute the console command
     */
    public function handle($input, $output)
    {
        $utility = $input->getArgument('utility');

        if (!ctype_upper($utility[0]))
        {
            $output->writeln("Error: Invalid Model. It must be PascalCase.");
            exit;
        }
        elseif (file_exists(config('path.utility.base') . "/{$utility}.php"))
        {
            $output->writeln("Error: The Model is already created.");
            exit;
        }

        $output->writeln($this->makeTemplate($utility) ? "Successfully created." : "File not created. Check the file path.");
    }

    /**
     * [Create utility template]
     * @depends handle
     * @param  [string] $utility [utility name]
     * @return [boolean]    [Return true if successfully creating file otherwise false]
     */
    private function makeTemplate($utility)
    {
        $file = config('path.console.foundation_command_base') . "/templates/utility.php.dist";
        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{namespace}}' => "{$this->namespace}",
                '{{utility}}' => $utility,
            ]);

            $file_path = config('path.utility.base') . "/{$utility}.php";

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