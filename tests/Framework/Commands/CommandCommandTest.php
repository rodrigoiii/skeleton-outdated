<?php

use Framework\Console\Commands\MakeCommandCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class CommandCommandTest extends TestCase
{
    protected static $tester;
    protected static $namespace;
    protected static $existing_commands;

    public static function setUpBeforeClass()
    {
        $app = new Application;
        $app->setAutoExit(false);
        $app->add(new MakeCommandCommand);

        self::$tester = new ApplicationTester($app);
        self::$namespace = config("app.namespace");
        self::$existing_commands = glob(app_path("Console/Commands/*.php"));
    }

    public function valid_command_provider()
    {
        $provider = json_decode(file_get_contents(__DIR__ . "/_files/command-provider.json"), true);

        return  array_map(function ($password) {
                    return [$password];
                }, $provider['valid']);
    }

    public function invalid_command_provider()
    {
        $provider = json_decode(file_get_contents(__DIR__ . "/_files/command-provider.json"), true);

        return  array_map(function ($password) {
                    return [$password];
                }, $provider['invalid']);
    }

    /**
     * @test
     * @dataProvider valid_command_provider
     */
    public function it_should_be_valid_command_name($command)
    {
        $this->assertTrue(ctype_upper($command[0]), "{$command} is not consider as valid.");
    }

    /**
     * @test
     * @dataProvider invalid_command_provider
     */
    public function it_should_be_invalid_command_name($command)
    {
        $this->assertFalse(ctype_upper($command[0]), "{$command} is not consider as invalid.");
    }

    /**
     * @test
     * @dataProvider valid_command_provider
     */
    public function command_must_be_worked_properly($command)
    {
        // to make sure command would not overwrite
        if (!in_array(app_path("Console/Commands/{$command}.php"), self::$existing_commands))
        {
            self::$tester->run(["make:command", '_command' => $command]);

            $this->assertFileExists(app_path("Console/Commands/{$command}.php"));
        }
    }

    /**
     * @test
     * @dataProvider valid_command_provider
     */
    public function content_should_be_correct($command)
    {
        $file = system_path("classes/Console/Commands/templates/command.php.dist");

        if (!in_array(app_path("Console/Commands/{$command}.php"), self::$existing_commands))
        {
            $content = strtr(file_get_contents($file), [
                '{{namespace}}' => self::$namespace,
                '{{command}}' => $command,
                '{{command_name}}' => strtolower($command)
            ]);

            $this->assertEquals($content, file_get_contents(app_path("Console/Commands/{$command}.php")));
        }
    }

    public static function tearDownAfterClass()
    {
        $command_files = glob(app_path("Console/Commands/*.php"));

        foreach ($command_files as $file) {
            if (!in_array($file, self::$existing_commands))
            {
                unlink($file);
            }
        }
    }
}