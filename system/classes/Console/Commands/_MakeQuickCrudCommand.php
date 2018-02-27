<?php

namespace Framework\Console\Commands;

use Framework\BaseCommand;

class _MakeQuickCrudCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "make:quick-crud {--m|model=}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Make quick-crud model. (special command)";

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
            elseif (file_exists(resources_path("views/{$model}-crud")))
            {
                throw new \Exception("quick-crud for {$model} is already created.", 1);
            }

            $this->makeModelTemplate($model);
            $this->makeViewTemplate($model);
            $this->makeControllerTemplate($model);
            $this->makeRequestTemplate($model);

            $this->showImportantNote($model);
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    /**
     * Make the template for model
     * @depends make
     */
    private function makeModelTemplate($model)
    {
        $model_template = strtr(file_get_contents(__DIR__ . "/templates/_quick-crud/model.php.dist"), [
            '{{namespace}}' => $this->namespace,
            '{{model}}' => $model
        ]);

        $file = fopen(app_path("Models/{$model}.php"), "w");
        fwrite($file, $model_template);
        fclose($file);

       echo "Create Model Done." . PHP_EOL;
    }

    /**
     * Make the template for view
     * @depends make
     */
    private function makeViewTemplate($model)
    {
        $model_singular = str_singular(strtolower($model));
        $model_plural = str_plural(strtolower($model));

        $quick_crud = file_get_contents(__DIR__ . "/templates/_quick-crud/views/layouts/quick-crud.twig.dist");
        $create = strtr(file_get_contents(__DIR__ . "/templates/_quick-crud/views/crud/create.twig.dist"), [
            '{{model_capital}}' => $model,
            '{{model_singular}}' => $model_singular
        ]);
        $edit = strtr(file_get_contents(__DIR__ . "/templates/_quick-crud/views/crud/edit.twig.dist"), [
            '{{model_capital}}' => $model,
            '{{model_singular}}' => $model_singular
        ]);
        $index = strtr(file_get_contents(__DIR__ . "/templates/_quick-crud/views/crud/index.twig.dist"), [
            '{{model_capital}}' => $model,
            '{{model_singular}}' => $model_singular,
            '{{model_plural}}' => $model_plural
        ]);
        $search = strtr(file_get_contents(__DIR__ . "/templates/_quick-crud/views/crud/search.twig.dist"), [
            '{{model_singular}}' => $model_singular,
            '{{model_plural}}' => $model_plural
        ]);
        $show = strtr(file_get_contents(__DIR__ . "/templates/_quick-crud/views/crud/show.twig.dist"), [
            '{{model_capital}}' => $model,
            '{{model_singular}}' => $model_singular
        ]);

        if (!file_exists(resources_path("views/{$model_singular}-crud")))
        {
            mkdir(resources_path("views/{$model_singular}-crud"));
        }

        if (!file_exists(resources_path("views/layouts/quick-crud.twig")))
        {
            $file = fopen(resources_path("views/layouts/quick-crud.twig"), "w");
            fwrite($file, $quick_crud);
            fclose($file);
        }

        $file = fopen(resources_path("views/{$model_singular}-crud/create.twig"), "w");
        fwrite($file, $create);
        fclose($file);

        $file = fopen(resources_path("views/{$model_singular}-crud/edit.twig"), "w");
        fwrite($file, $edit);
        fclose($file);

        $file = fopen(resources_path("views/{$model_singular}-crud/index.twig"), "w");
        fwrite($file, $index);
        fclose($file);

        $file = fopen(resources_path("views/{$model_singular}-crud/search.twig"), "w");
        fwrite($file, $search);
        fclose($file);

        $file = fopen(resources_path("views/{$model_singular}-crud/show.twig"), "w");
        fwrite($file, $show);
        fclose($file);

       echo "Create View Done." . PHP_EOL;
    }

    /**
     * Make the template for controller
     * @depends make
     */
    private function makeControllerTemplate($model)
    {
        $model_singular = strtolower(str_singular($model));
        $model_plural = strtolower(str_plural($model));

        $controller_template = strtr(file_get_contents(__DIR__ . "/templates/_quick-crud/controller.php.dist"), [
            '{{namespace}}' => $this->namespace,
            '{{model_capital}}' => $model,
            '{{model_singular}}' => $model_singular,
            '{{model_plural}}' => $model_plural
        ]);
        $registered_template = strtr(file_get_contents(__DIR__ . "/templates/controller/controller-container.php.dist"), [
            '{{controller}}' => "{$model}Controller",
            '{{pre_controller_namespace}}' => ""
        ]);

        // create file
        $file = fopen(app_path("Http/Controllers/{$model}Controller.php"), "w");
        fwrite($file, $controller_template);
        fclose($file);

        // register the controller in container
        $file = fopen(settings_path("registered-controllers.php"), "a");
        fwrite($file, $registered_template);
        fclose($file);

        echo "Create Controller Done." . PHP_EOL;
    }

    /**
     * Make the template for request
     * @depends make
     */
    private function makeRequestTemplate($model)
    {
        $request_template = strtr(file_get_contents(__DIR__ . "/templates/_quick-crud/request.php.dist"), [
            '{{namespace}}' => $this->namespace,
            '{{model}}' => $model
        ]);

        $file = fopen(app_path("Http/Requests/{$model}Request.php"), "w");
        fwrite($file, $request_template);
        fclose($file);

       echo "Create Request Done." . PHP_EOL;
    }

    /**
     * Display the important note
     * @depend handle
     * @return [void]
     */
    private function showImportantNote($model)
    {
        $model_singular = strtolower(str_singular($model));
        $model_plural = strtolower(str_plural($model));

        echo strtr(file_get_contents(__DIR__ . "/templates/_quick-crud/important-note.txt.dist"), [
            '{{model_capital}}' => $model,
            '{{model_singular}}' => $model_singular,
            '{{model_plural}}' => $model_plural
        ]);
    }
}