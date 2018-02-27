<?php

namespace Framework\Console\Commands;

use Framework\BaseCommand;

class MakeTestCommand extends BaseCommand
{
    private $signature = "make:test {test} {--t|type= : [command]}";

    private $description = "Create phpunit test class template.";

    public function __construct()
    {
        parent::__construct($this->signature, $this->description);
    }

    public function handle($input, $output)
    {
        $test = $input->getArgument('test');
        $type = $input->getOption('type');

        $pre_test_path = base_path("tests");

        // have directory
        if (strpos($test, "/"))
        {
            $explode_test = explode("/", $test);
            $test = array_pop($explode_test);

            $pre_test_path = base_path("tests/" . implode("/", $explode_test));

            // create directory
            if (!file_exists($pre_test_path))
                mkdir($pre_test_path, 0755, true);
        }

        try {
            if (!ctype_upper($test[0]))
                throw new \Exception("Error: Invalid Test. It must be Characters and PascalCase.", 1);

            if (file_exists("{$pre_test_path}/{$test}.php"))
                throw new \Exception("Error: The Test is already created.", 1);

            $output->writeln($this->makeTemplate($pre_test_path, $test, $type) ? "Successfully created." : "File not created. Check the file path.");
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    /**
     * [Create test template]
     * @depends handle
     * @param  [string] $pre_test_path [the pre string represent as folder before the file]
     * @param  [string] $test [test class name]
     * @param  [string] $type [test type]
     * @return [boolean]    [Return true if successfully creating file otherwise false]
     */
    private function makeTemplate($pre_test_path, $test, $type)
    {
        switch ($type) {
            case 'command':
                $filename = "test-command.php.dist";
                break;

            default:
                $filename = "test.php.dist";
                break;
        }

        $file = __DIR__ . "/templates/test/{$filename}";

        if (file_exists($file))
        {
            $template = strtr(file_get_contents($file), [
                '{{test}}' => $test
            ]);

            $file_path = "{$pre_test_path}/{$test}.php";

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
