<?php

namespace Console\Commands;

class TestCommand extends BaseCommand
{
    private $signature = "make:test {test}";

    private $description = "Create phpunit test class template.";

    public function __construct()
    {
        parent::__construct($this->signature, $this->description);
    }

    public function handle($input, $output)
    {
        $test = $input->getArgument('test');

        // have directory
        if (strpos($test, "/"))
        {
            $explode_test = explode("/", $test);
            $test = array_pop($explode_test);

            $pre_test_path = base_path("tests/" . implode("/", $explode_test));

            // create directory
            if (!file_exists($pre_test_path))
            {
                mkdir($pre_test_path, 0755, true);
            }
        }
        else
        {
            $pre_test_path = base_path("tests");
        }

        if (!ctype_upper($test[0]))
        {
            $output->writeln("Error: Invalid Model. It must be PascalCase.");
            exit;
        }
        elseif (file_exists("{$pre_test_path}/{$test}Test.php"))
        {
            $output->writeln("Error: The Model is already created.");
            exit;
        }

        $output->writeln($this->makeTemplate($pre_test_path, $test) ? "Successfully created." : "File not created. Check the file path.");
    }

    /**
     * [Create test template]
     * @depends handle
     * @param  [string] $pre_test_path [the pre string represent as folder before the file]
     * @param  [string] $test [test name]
     * @return [boolean]    [Return true if successfully creating file otherwise false]
     */
    private function makeTemplate($pre_test_path, $test)
    {
        $file = core_path("psr-4/Console/Commands/templates/test.php.dist");
        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{test}}' => $test
            ]);

            $file_path = "{$pre_test_path}/{$test}Test.php";

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
