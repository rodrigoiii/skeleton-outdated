<?php

namespace Console\Commands;

class ApiCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "api {action} {api} {--o|option=false : [false | model | resource]}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Create api class template.";

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
        $action = $input->getArgument('action');
        $api = $input->getArgument('api');
        $option = $input->getOption('option');

        // have directory
        if (strpos($api, "/"))
        {
            $explode_api = explode("/", $api);
            $api = array_pop($explode_api);

            $api_namespace = implode("\\",$explode_api) . "\\";

            $pre_api_path = config("path.api.base") . "/" . implode("/", $explode_api);
            $top_template = "namespace {$this->namespace}\Http\Controllers\Api\\" . implode("\\", $explode_api) . ";\n\n";
            $top_template .= "use {$this->namespace}\Http\Controllers\Controller;";

            // create directory
            if (!file_exists($pre_api_path))
            {
                mkdir($pre_api_path, 0755, true);
            }
        }
        else
        {
            $pre_api_path = config("path.api.base");
            $top_template = "namespace {$this->namespace}\Http\Controllers;";

            $api_namespace = "";
        }

        $file = $pre_api_path . "/{$api}Controller.php";

        if ($action === "make")
        {
            if ($api[0] !== "_" && ! ctype_upper($api[0]) )
            {
                $output->writeln("Error: Invalid Api. It must be PascalCase.");
                exit;
            }
            elseif (file_exists($file))
            {
                $output->writeln("Error: The Api is already created.");
                exit;
            }

            // create file
            $file = fopen($pre_api_path . "/{$api}Controller.php", "w");
            fwrite($file, $this->getTemplate($top_template, $api, $option));
            fclose($file);

            // register the controller in container
            $api_register_path = config('path.registered.api_base') . "/registered-api.php";
            $str = "\n" .
            "# " . $api_namespace . "{$api}Controller" . "\n" .
            "\$container['" . $api_namespace . "{$api}Controller'] = function (\$c)" . "\n" .
            "{" . "\n" .
            "   return new {$this->namespace}\Http\Controllers\Api\\" . $api_namespace . "{$api}Controller();" . "\n" .
            "};" . "\n\n";

            $file = fopen($api_register_path, "a");
            fwrite($file, $str);
            fclose($file);

            $output->writeln("Successfully created.");
        }
        elseif ($action === "remove")
        {
            if (!file_exists($file))
            {
                $output->writeln("Error: " . $api . " is not exist.");
                exit;
            }

            unlink($file);

            if (count(glob(dirname($file) . "/*.php")) === 0)
            {
                rmdir(dirname($file));
            }

            $file = config('path.registered.api_base') . "/registered-api.php";
            $search = "\n# " . $api_namespace . "{$api}Controller\n\$container['" . $api_namespace . "{$api}Controller'] = function (\$c)\n{\n\treturn new {$this->namespace}\Http\Controllers\Api\\" . $api_namespace . "{$api}Controller();\n};\n\n";

            $content = file_get_contents($file);
            $content = str_replace($search, "", $content);
            file_put_contents($file, $content);

            $output->writeln("Successfully deleted.");
        }
        else
        {
            $output->writeln("Error: Invalid action. It must be 'create' or 'delete'");
            exit;
        }
    }

    private function getTemplate($top_template, $api, $option)
    {
        switch ($option) {
            case 'model':
                $template_file_name = "api-model";
                break;

            case 'resource':
                $template_file_name = "api-with-resource";
                break;

            default:
                $template_file_name = "api";
        }

        $file = config("path.console.foundation_command_base") . "/templates/Api/{$template_file_name}.php.dist";

        if (file_exists($file))
        {
            $template = file_get_contents($file);

            if ($option === "model")
            {
                return strtr($template, [
                    '{{model}}' => $api,
                    '{{model_singular}}' => strtolower(str_singular($api)),
                    '{{model_plural}}' => strtolower(str_plural($api)),
                ]);
            }
            else
            {
                return strtr($template, [
                    '{{top_template}}' => $top_template,
                    '{{api}}' => $api
                ]);
            }
        }
        else
        {
            exit("{{$file}} file is not exist.");
        }

        return false;
    }
}