<?php

namespace Framework\Utilities;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{
    private static function logger()
    {
		$settings = [
            'name' => config('app.name'),
            'level' => Logger::DEBUG,
            'path' => storage_path("logs/app.log")
        ];

		$log = new Logger($settings['name']);
		return $log->pushHandler(new StreamHandler($settings['path']), $settings['level']);
    }

    public static function debug($message)
    {
		return static::logger()->debug($message);
	}

    public static function info($message)
    {
        return static::logger()->info($message);
    }

    public static function notice($message)
    {
        return static::logger()->notice($message);
    }

    public static function warning($message)
    {
        return static::logger()->warning($message);
    }

    public static function error($message)
    {
        return static::logger()->error($message);
    }

    public static function alert($message)
    {
        return static::logger()->alert($message);
    }

    public static function emergency($message)
    {
        return static::logger()->emergency($message);
    }
}