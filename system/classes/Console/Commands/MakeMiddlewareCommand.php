<?php

namespace Framework\Console\Commands;

use Framework\BaseCommand;

class MakeMiddlewareCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "make:middleware {middleware}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Create middleware class template.";

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
        $middleware = $input->getArgument('middleware');

        $pre_middleware_path = app_path("Http/Middlewares");
        $sub_directories = "";

        // have directory
        if (strpos($middleware, "/"))
        {
            $explode_middleware = explode("/", $middleware);
            $middleware = array_pop($explode_middleware);

            $pre_middleware_path = app_path("Http/Middlewares/" . implode("/", $explode_middleware));
            $sub_directories = "\\" . implode("\\", $explode_middleware);

            // create directory
            if (!file_exists($pre_middleware_path))
                mkdir($pre_middleware_path, 0755, true);
        }

        try {
            if (!ctype_upper($middleware[0]))
                throw new \Exception("Error: Invalid Middleware. It must be Characters and PascalCase.", 1);

            if (file_exists(app_path("{$pre_middleware_path}/{$middleware}.php")))
                throw new \Exception("Error: The Middleware is already created.", 1);

            $output->writeln($this->makeTemplate($sub_directories, $pre_middleware_path, $middleware) ? "Successfully created." : "File not created. Check the file path.");
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    /**
     * [Create middleware template]
     * @depends handle
     * @param  [string] $sub_directories [sub directories of class]
     * @param  [string] $pre_middleware_path [the pre string represent as folder before the file]
     * @param  [string] $middleware [middleware name]
     * @return [boolean]    [Return true if successfully creating file otherwise false]
     */
    private function makeTemplate($sub_directories, $pre_middleware_path, $middleware)
    {
        $file = __DIR__ . "/templates/middleware.php.dist";
        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{namespace}}' => $this->namespace,
                '{{sub_directories}}' => $sub_directories,
                '{{middleware}}' => $middleware
            ]);

            $file_path = "{$pre_middleware_path}/{$middleware}.php";

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