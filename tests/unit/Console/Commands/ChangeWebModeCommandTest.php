<?php

use Console\Commands\ChangeWebModeCommand;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class ChangeWebModeCommandTest extends TestCase
{
    protected static $tester;
    protected static $dotenv;
    protected static $old_mode;

    public static function setUpBeforeClass()
    {
        $app = new Application;
        $app->setAutoExit(false);
        $app->add(new ChangeWebModeCommand);

        self::$tester = new ApplicationTester($app);
        self::$dotenv = new Dotenv(base_path());
        self::$dotenv->overload();
        self::$old_mode = _env('WEB_MODE');
    }

    public function setUp()
    {
        self::$dotenv->overload();
    }

    /**
     * @test
     */
    public function it_will_change_to_up_mode()
    {
        self::$tester->run(["web-mode", 'mode' => "up"]);
        self::$dotenv->overload();

        $this->assertEquals("up", strtolower(_env('WEB_MODE')));
    }

    /**
     * @test
     */
    public function it_will_change_to_down_mode()
    {
        self::$tester->run(["web-mode", 'mode' => "down"]);
        self::$dotenv->overload();

        $this->assertEquals("down", strtolower(_env('WEB_MODE')));
    }

    public static function tearDownAfterClass()
    {
        self::$dotenv->overload();
        self::$tester->run(['web-mode', 'mode' => strtolower(self::$old_mode)]);
    }
}