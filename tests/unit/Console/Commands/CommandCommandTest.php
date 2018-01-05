<?php

use Console\Commands\CommandCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class CommandCommandTest extends TestCase
{
    protected static $tester;
    protected static $namespace;

    public static function setUpBeforeClass()
    {
        $app = new Application;
        $app->setAutoExit(false);
        $app->add(new CommandCommand);

        self::$tester = new ApplicationTester($app);
        self::$namespace = config("app.namespace");
    }

    public function commands_data()
    {
        return [
            [["A", "B", "AB"]]
        ];
    }

    /**
     * @test
     * @dataProvider commands_data
     */
    public function it_will_create_file($commands)
    {
        foreach ($commands as $command) {
            self::$tester->run(["make:command", '_command' => $command]);
            $this->assertFileExists(app_path("Console/Commands/{$command}Command.php"));
        }
    }

    /**
     * @test
     * @dataProvider commands_data
     */
    public function content_is_correct($commands)
    {
        $file = core_path("psr-4/Console/Commands/templates/command.php.dist");

        foreach ($commands as $command) {
            $content = strtr(file_get_contents($file, [
                '{{namespace}}' => self::$namespace,
                '{{command}}' => $command,
                '{{command_name}}' => strtolower($command)
            ]));

            $this->assertEquals($content, file_get_contents(app_path("Console/Commands/{$command}Command.php")));
        }
    }

    // /**
    //  * @test
    //  * @dataProvider commands_data
    //  */
    // public function it_will_not_create_file()
    // {

    // }
}