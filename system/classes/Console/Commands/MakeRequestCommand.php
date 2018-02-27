<?php

namespace Framework\Console\Commands;

use Framework\BaseCommand;

class MakeRequestCommand extends BaseCommand
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

        try {
            if ($request[0] !== "_" && ! ctype_upper($request[0]) )
                throw new \Exception("Error: Invalid Request. It must be Characters and PascalCase.", 1);

            if (file_exists(app_path("Http/Requests/{$request}.php")))
                throw new \Exception("Error: The Request is already created.", 1);

            $output->writeln($this->makeTemplate($request) ? "Successfully created." : "File not created. Check the file path.");
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    /**
     * [Create request template]
     * @depends handle
     * @param  [string] $request [request name]
     * @return [boolean]    [Return true if successfully creating file otherwise false]
     */
    private function makeTemplate($request)
    {
        $file = __DIR__ . "/templates/request.php.dist";
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