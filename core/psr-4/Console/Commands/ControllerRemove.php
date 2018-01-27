<?php

namespace Console\Commands;

class ControllerRemoveCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "remove:controller {controller}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Remove controller class template and unregistered it in slim container.";

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
            $registered_controller_file = core_path("settings/registered-controllers.php");
            $registered_template = strtr(file_get_contents(core_path("psr-4/Console/Commands/templates/controller/controller-container.php.dist")), [
                '{{controller}}' => $controller,
                '{{pre_controller_namespace}}' => $pre_controller_namespace
            ]);

            if (!file_exists($file))
                throw new \Exception("Error: " . $controller . " is not exist.", 1);

            // remove file
            unlink($file);

            // remove it folder if there's no php file
            if (count(glob(dirname($file) . "/*.php")) === 0 && dirname($file) !== app_path("Http/Controllers"))
            {
                rmdir(dirname($file));
            }

            $content = file_get_contents($registered_controller_file);
            file_put_contents($registered_controller_file, str_replace($registered_template, "", $content));

            $output->writeln(!file_exists($file) ? "Successfully deleted.": "File is not deleted.");
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}
