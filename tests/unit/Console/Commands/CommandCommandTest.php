<?php

use Console\Commands\CommandCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class CommandCommandTest extends TestCase
{
    protected static $tester;
    protected static $namespace;
    protected static $commands_data;

    public static function setUpBeforeClass()
    {
        $app = new Application;
        $app->setAutoExit(false);
        $app->add(new CommandCommand);

        self::$tester = new ApplicationTester($app);
        self::$namespace = config("app.namespace");
        self::$commands_data = ["A", "A", "B", "B", "_", "a", "b"];
    }

    /**
     * @test
     */
    public function it_will_create_file()
    {
        $commands = self::$commands_data;

        foreach ($commands as $command) {
            if (!file_exists(app_path("Console/Commands/{$command}Command.php")))
            {
                self::$tester->run(["make:command", '_command' => $command]);

                if (ctype_upper($command[0]))
                {
                    $this->assertFileExists(app_path("Console/Commands/{$command}Command.php"));
                }
                else
                {
                    $this->assertFalse(file_exists(app_path("Console/Commands/{$command}Command.php")));
                }
            }
        }
    }

    /**
     * @test
     */
    public function content_is_correct()
    {
        $commands = self::$commands_data;
        $file = core_path("psr-4/Console/Commands/templates/command.php.dist");

        foreach ($commands as $command) {
            if (ctype_upper($command[0]) && file_exists(app_path("Console/Commands/{$command}Command.php")))
            {
                $content = strtr(file_get_contents($file), [
                    '{{namespace}}' => self::$namespace,
                    '{{command}}' => $command,
                    '{{command_name}}' => strtolower($command)
                ]);

                $this->assertEquals($content, file_get_contents(app_path("Console/Commands/{$command}Command.php")));
            }
        }
    }

    public static function tearDownAfterClass()
    {
        $commands = self::$commands_data;

        foreach ($commands as $command) {
            if (ctype_upper($command[0]) && file_exists(app_path("Console/Commands/{$command}Command.php")))
            {
                unlink(app_path("Console/Commands/{$command}Command.php"));
            }
        }
    }
}