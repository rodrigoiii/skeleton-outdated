<?php

use Console\Commands\GenerateKeyCommand;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class GenerateKeyTest extends TestCase
{
    protected static $tester;
    protected static $dotenv;
    protected static $old_app_key;

    public static function setUpBeforeClass()
    {
        $app = new Application;
        $app->setAutoExit(false);
        $app->add(new GenerateKeyCommand);

        self::$tester = new ApplicationTester($app);
        self::$dotenv = new Dotenv(base_path());
        self::$dotenv->overload();

        self::$old_app_key = config('app.key');
        Log::write('debug', self::$old_app_key);
    }

    public function setUp()
    {
        self::$dotenv->overload();
    }

    /**
     * @test
     */
    public function it_will_change_app_key()
    {
        self::$tester->run(["key:generate"]);
        self::$dotenv->overload();

        Log::write('debug', _env('APP_KEY'));
        $this->assertFalse(_env('APP_KEY') === self::$old_app_key);
    }
}