<?php

namespace Framework\Console\Commands;

use Framework\BaseCommand;

class MakeControllerCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "make:controller {controller} {--r|resource}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Create controller class template and registered it in slim container.";

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
        $controller = $input->getArgument('controller');
        $resource = $input->getOption('resource');

        try {
            $pre_controller_path = app_path("Http/Controllers");
            $sub_directories = "";
            $pre_controller_namespace = "";

            // have directory
            if (strpos($controller, "/"))
            {
                $explode_controller = explode("/", $controller);
                $controller = array_pop($explode_controller);

                $pre_controller_path = app_path("Http/Controllers/" . implode("/", $explode_controller));
                $sub_directories = "\\" . implode("\\", $explode_controller);
                $pre_controller_namespace = implode("\\", $explode_controller) . "\\";

                // create directory
                if (!file_exists($pre_controller_path))
                    mkdir($pre_controller_path, 0755, true);
            }

            if (!ctype_upper($controller[0]))
                throw new \Exception("Error: Invalid Controller. It must be Characters and PascalCase.", 1);

            $file = "{$pre_controller_path}/{$controller}.php";
            $registered_controller_file = settings_path("registered-controllers.php");
            $registered_template = strtr(file_get_contents(__DIR__ . "/templates/controller/controller-container.php.dist"), [
                '{{controller}}' => $controller,
                '{{pre_controller_namespace}}' => $pre_controller_namespace
            ]);

            if (file_exists($file))
                throw new \Exception("Error: The Controller is already created.", 1);

            $template = $this->getTemplate($sub_directories, $controller, $resource);
            if ($template !== false)
            {
                // create file
                $file_controller = fopen($pre_controller_path . "/{$controller}.php", "w");
                fwrite($file_controller, $template);
                fclose($file_controller);

                // register the controller in container
                $file_registered_controller = fopen($registered_controller_file, "a");
                fwrite($file_registered_controller, $registered_template);
                fclose($file_registered_controller);
            }

            $output->writeln(file_exists($file) ? "Successfully created." : "File is not created.");
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    private function getTemplate($sub_directories, $controller, $is_resource = false)
    {
        $file = __DIR__ . "/templates/controller/controller" . ($is_resource ? "-with-resource" : "") . ".php.dist";

        if (file_exists($file))
        {
            $template = file_get_contents($file);

            return strtr($template, [
                '{{namespace}}' => $this->namespace,
                '{{sub_directories}}' => $sub_directories,
                '{{controller}}' => $controller
            ]);
        }
        else
        {
            exit("{{$file}} file is not exist.");
        }

        return false;
    }
}
