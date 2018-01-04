<?php

namespace Console\Commands;

class RequestCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "make:request {request}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Create request class template.";

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
        $request = $input->getArgument('request');

        if ($request[0] !== "_" && ! ctype_upper($request[0]) )
        {
            $output->writeln("Error: Invalid Request. It must be PascalCase.");
            exit;
        }
        elseif (file_exists(app_path("Http/Requests/{$request}.php")))
        {
            $output->writeln("Error: The Request is already created.");
            exit;
        }

        $output->writeln($this->makeTemplate($request) ? "Successfully created." : "File not created. Check the file path.");
    }

    /**
     * [Create request template]
     * @depends handle
     * @param  [string] $request [request name]
     * @return [boolean]    [Return true if successfully creating file otherwise false]
     */
    private function makeTemplate($request)
    {
        $file = core_path("psr-4/Console/Commands/templates/request.php.dist");
        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{namespace}}' => "$this->namespace",
                '{{request}}' => $request
            ]);

            $file_path = app_path("Http/Requests/{$request}.php");

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