<?php

namespace Console\Commands;

class ValidatorCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "make:validator {validator} {--e|error_message=}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Create validator class template.";

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
        $validator = $input->getArgument('validator');
        $error_message = $input->getOption('error_message');

        if (!ctype_upper($validator[0]))
        {
            $output->writeln("Error: Invalid Validator. It must be PascalCase.");
            exit;
        }
        elseif (file_exists(app_path("Validation/Rules/{$validator}.php")))
        {
            $output->writeln("Error: The Validator is already created.");
            exit;
        }

        $output->writeln($this->ruleTemplate($validator) ? "Successfully created rule class." : "Rule file not created. Check the file path.");
        $output->writeln($this->exceptionTemplate($validator, $error_message) ? "Successfully created exception class." : "Exception file not created. Check the file path.");
    }

    private function ruleTemplate($validator)
    {
        $file = core_path("psr-4/Console/Commands/templates/validator/rule.php.dist");

        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{namespace}}' => "$this->namespace",
                '{{validator}}' => $validator
            ]);

            $file_path = app_path("Validation/Rules/{$validator}.php");

            $file = fopen($file_path, "w");
            fwrite($file, $template);
            fclose($file);

            return file_exists($file_path);
        }
        else
        {
            exit("{$file} file is not exist.");
        }

        return false;
    }

    private function exceptionTemplate($validator, $error_message)
    {
        $file = core_path("psr-4/Console/Commands/templates/validator/exception.php.dist");

        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{namespace}}' => "$this->namespace",
                '{{validator}}' => $validator,
                '{{error_message}}' => "\"$error_message\""
            ]);

            $file_path = app_path("Validation/Exceptions/{$validator}Exception.php");

            $file = fopen($file_path, "w");
            fwrite($file, $template);
            fclose($file);

            return file_exists($file_path);
        }
        else
        {
            exit("{$file} file is not exist.");
        }

        return false;
    }
}