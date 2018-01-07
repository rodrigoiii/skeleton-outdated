<?php

namespace Console\Commands;

class MiddlewareCommand extends BaseCommand
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

        try {
            if (!ctype_upper($middleware[0]))
                throw new \Exception("Error: Invalid Middleware. It must be Characters and PascalCase.", 1);

            if (file_exists(app_path("Http/Middlewares/{$middleware}.php")))
                throw new \Exception("Error: The Middleware is already created.", 1);

            $output->writeln($this->makeTemplate($middleware) ? "Successfully created." : "File not created. Check the file path.");
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    /**
     * [Create middleware template]
     * @depends handle
     * @param  [string] $middleware [middleware name]
     * @return [boolean]    [Return true if successfully creating file otherwise false]
     */
    private function makeTemplate($middleware)
    {
        $file = core_path("psr-4/Console/Commands/templates/middleware.php.dist");
        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{namespace}}' => "{$this->namespace}",
                '{{middleware}}' => $middleware
            ]);

            $file_path = app_path("Http/Middlewares/{$middleware}.php");

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