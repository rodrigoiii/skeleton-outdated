<?php

use App\Console\Commands\HelloCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Console\Tester\CommandTester;

class HelloCommandTest extends TestCase
{
    protected $tester;

    public function setUp()
    {
        $app = new Application;
        $app->setAutoExit(false);
        $app->add(new HelloCommand);

        $this->tester = new ApplicationTester($app);
        $this->tester->run(["greet:hello"]);
    }

    /**
     * @test
     */
    public function it_will_print_hello_world()
    {
        $this->expectOutputString("Hello World" . PHP_EOL);
        echo $this->tester->getDisplay();
    }
}