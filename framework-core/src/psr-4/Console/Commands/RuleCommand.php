<?php

namespace Framework\Console\Commands;

use Framework\BaseCommand;

class RuleCommand extends BaseCommand
{
    /**
     * Console command signature
     * @var string
     */
    private $signature = "make:rule {rule} {--e|error_message=}";

    /**
     * Console command description
     * @var string
     */
    private $description = "Create rule and exception class template.";

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
        $rule = $input->getArgument('rule');
        $error_message = $input->getOption('error_message');

        try {
            if (!ctype_upper($rule[0]))
                throw new \Exception("Error: Invalid Validator. It must be Characters and PascalCase.", 1);

            if (file_exists(app_path("Validation/Rules/{$rule}.php")))
                throw new \Exception("Error: The Validator is already created.", 1);

            $output->writeln($this->ruleTemplate($rule) ? "Successfully created rule class." : "Rule file not created. Check the file path.");
            $output->writeln($this->exceptionTemplate($rule, $error_message) ? "Successfully created exception class." : "Exception file not created. Check the file path.");
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    private function ruleTemplate($rule)
    {
        $file = __DIR__ . "/templates/validator/rule.php.dist";

        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{namespace}}' => "$this->namespace",
                '{{rule}}' => $rule
            ]);

            $file_path = app_path("Validation/Rules/{$rule}.php");

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

    private function exceptionTemplate($rule, $error_message)
    {
        $file = __DIR__ . "/templates/validator/exception.php.dist";

        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{namespace}}' => "$this->namespace",
                '{{rule}}' => $rule,
                '{{error_message}}' => "\"$error_message\""
            ]);

            $file_path = app_path("Validation/Exceptions/{$rule}Exception.php");

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