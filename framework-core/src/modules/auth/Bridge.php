<?php

namespace Framework\Auth;

class Bridge
{
    public static function config($key = "")
    {
        global $container;

        if (empty($key))
        {
            return $container['settings']['_auth'];
        }

        $active = $container['settings']['_auth'];

        $temp = explode(".", $key);
        $keys = $temp;

        foreach ($keys as $key) {
            $active = $active[$key];
        }
        return $active;
    }

    public static function model($model)
    {
        return static::config('model_namespace') . "\\{$model}";
    }

    public static function controller($controller)
    {
        return static::config('controller_namespace') . "\\{$controller}";
    }

    public static function middleware($middleware)
    {
        return static::config('middleware_namespace') . "\\{$middleware}";
    }
}