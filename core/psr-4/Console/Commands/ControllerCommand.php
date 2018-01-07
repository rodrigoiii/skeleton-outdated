<?php

namespace Console\Commands;

class ControllerCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "controller {action} {controller} {--r|resource}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Create or remove controller class template.";

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
        $action = $input->getArgument('action');
        $controller = $input->getArgument('controller');
        $resource = $input->getOption('resource');

        try {
            if (!in_array($action, ["make", "remove"]))
                throw new \Exception("Error: Invalid action. It must be 'make' or 'remove'", 1);

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

            $file = "{$pre_controller_path}/{$controller}Controller.php";
            $registered_controller_file = core_path("settings/registered-controllers.php");
            $template = strtr(file_get_contents(core_path("psr-4/Console/Commands/templates/controller/controller-container.php.dist")), [
                '{{controller}}' => $controller,
                '{{pre_controller_namespace}}' => $pre_controller_namespace
            ]);

            if ($action === "make")
            {
                if (file_exists($file))
                    throw new \Exception("Error: The Controller is already created.", 1);

                // create file
                $file = fopen($pre_controller_path . "/{$controller}Controller.php", "w");
                fwrite($file, $this->getTemplate($sub_directories, $controller, $resource));
                fclose($file);

                // register the controller in container
                $file = fopen($registered_controller_file, "a");
                fwrite($file, $template);
                fclose($file);

                $output->writeln("Successfully created.");
            }
            else // $action is remove
            {
                if (!file_exists($file))
                    throw new \Exception("Error: " . $controller . " is not exist.", 1);

                unlink($file);

                if (count(glob(dirname($file) . "/*.php")) === 0)
                    rmdir(dirname($file));

                $content = file_get_contents($registered_controller_file);
                file_put_contents($registered_controller_file, str_replace($template, "", $content));

                $output->writeln("Successfully deleted.");
            }
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    private function getTemplate($sub_directories, $controller, $is_resource = false)
    {
        $file = core_path("psr-4/Console/Commands/templates/controller/controller" . ($is_resource ? "-with-resource" : "") . ".php.dist");

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
