<?php

namespace Console\Commands;

class ModelCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "make:model {model}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Create model class template.";

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
        $model = $input->getArgument('model');

        // have directory
        if (strpos($model, "/"))
        {
            $explode_model = explode("/", $model);
            $model = array_pop($explode_model);

            $contoller_namespace = implode("\\",$explode_model) . "\\";

            $pre_model_path = app_path("Models/" . implode("/", $explode_model));
            $top_template = "namespace {$this->namespace}\Models\\" . implode("\\", $explode_model) . ";\n+.";

            // create directory
            if (!file_exists($pre_model_path))
            {
                mkdir($pre_model_path, 0755, true);
            }
        }
        else
        {
            $pre_model_path = app_path("Models");
            $top_template = "namespace {$this->namespace}\Http\Controllers;";

            $contoller_namespace = "";
        }

        if (!ctype_upper($model[0]))
        {
            $output->writeln("Error: Invalid Model. It must be PascalCase.");
            exit;
        }
        elseif (file_exists("{$pre_model_path}/{$model}.php"))
        {
            $output->writeln("Error: The Model is already created.");
            exit;
        }

        $output->writeln($this->makeTemplate($top_template, $pre_model_path, $model) ? "Successfully created." : "File not created. Check the file path.");
    }

    /**
     * [Create model template]
     * @depends handle
     * @param  [string] $top_template [template at the top part]
     * @param  [string] $pre_model_path [the pre string represent as folder before the file]
     * @param  [string] $model [model name]
     * @return [boolean]    [Return true if successfully creating file otherwise false]
     */
    private function makeTemplate($top_template, $pre_model_path, $model)
    {
        $file = core_path("psr-4/Console/Commands/templates/model.php.dist");
        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{namespace}}' => $this->namespace,
                '{{model}}' => $model
            ]);

            $file_path = "{$pre_model_path}/{$model}.php";

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
