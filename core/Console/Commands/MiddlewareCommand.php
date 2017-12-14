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
        $this->namespace = _env('APP_NAMESPACE', "App");

        parent::__construct($this->signature, $this->description);
    }

    /**
     * Execute the console command
     */
    public function handle($input, $output)
    {
        $middleware = $input->getArgument('middleware');

        if ($middleware[0] !== "_" && ! ctype_upper($middleware[0]) )
        {
            $output->writeln("Error: Invalid Middleware. It must be PascalCase.");
            exit;
        }
        elseif (file_exists(config('path.middleware.base') . "/{$middleware}.php"))
        {
            $output->writeln("Error: The Middleware is already created.");
            exit;
        }

        $output->writeln($this->makeTemplate($middleware) ? "Successfully created." : "File not created. Check the file path.");
    }

    /**
     * [Create middleware template]
     * @depends handle
     * @param  [string] $middleware [middleware name]
     * @return [boolean]    [Return true if successfully creating file otherwise false]
     */
    private function makeTemplate($middleware)
    {
        $file = config('path.console.foundation_command_base') . "/templates/middleware.php.dist";
        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{namespace}}' => "{$this->namespace}",
                '{{middleware}}' => $middleware
            ]);

            $file_path = config('path.middleware.base') . "/{$middleware}.php";

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