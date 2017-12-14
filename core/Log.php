<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{
	public static function write($method, $message)
	{
		$settings = config('monolog');

		$log = new Logger($settings['name']);
		$log->pushHandler(new StreamHandler($settings['path']), $settings['level']);

		$log->$method($message);
	}
}