<?php

namespace Framework\Console\Commands;

use Framework\BaseCommand;

class _RemoveQuickCrudCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "remove:quick-crud {--m|model=}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Remove quick-crud model. (special command)";

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
        $model = $input->getOption('model');

        try {
            if (!ctype_upper($model[0]))
            {
                throw new \Exception("{$model} must be capitalized.", 1);
            }
            elseif (!file_exists(resources_path("views/{$model}-crud")))
            {
                throw new \Exception("quick-crud for {$model} is already deleted.", 1);
            }

            $this->removeModel($model);
            $this->removeView($model);
            $this->removeAndUnregisterController($model);
            $this->removeRequest($model);
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    /**
     * Uninstall model of quick-crud module
     * @depends remove
     */
    private function removeModel($model)
    {
        unlink(app_path("Models/{$model}.php")); // remove model
        echo "Removing Model Done." . PHP_EOL;
    }

    /**
     * Uninstall view of quick-crud module
     * @depends remove
     */
    private function removeView($model)
    {
        $lower_model = strtolower($model);
        rmdir_recursion(resources_path("views/{$lower_model}-crud"));

        if (count(glob(resources_path("views/*-crud/*"))) === 0)
        {
            unlink(resources_path("views/layouts/quick-crud.twig"));
        }

        echo "Removing View Done." . PHP_EOL;
    }

    /**
     * Uninstall controller of quick-crud module
     * @depends remove
     */
    private function removeAndUnregisterController($model)
    {
        $registered_controller_file = settings_path("registered-controllers.php");
        $content = file_get_contents($registered_controller_file);
        $registered_template = strtr(file_get_contents(__DIR__ . "/templates/controller/controller-container.php.dist"), [
            '{{controller}}' => "{$model}Controller",
            '{{pre_controller_namespace}}' => ""
        ]);

        file_put_contents($registered_controller_file, str_replace($registered_template, "", $content));
        unlink(app_path("Http/Controllers/{$model}Controller.php"));

        echo "Removing Controller Done." . PHP_EOL;
    }

    /**
     * Uninstall request of quick-crud module
     * @depends remove
     */
    private function removeRequest($model)
    {
        unlink(app_path("Http/Requests/{$model}Request.php")); // remove model
        echo "Removing Model Done." . PHP_EOL;
    }
}