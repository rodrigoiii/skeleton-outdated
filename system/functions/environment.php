<?php

/**
 * Enhance the getenv function
 * @param  [string] $key   [the key in the .env]
 * @param  [string] $value [to be the value]
 * @return [string]        [return the content if it is set, otherwise the var value]
 */
function _env($key, $value = "")
{
    return !empty(getenv($key)) ? getenv($key) : $value;
}

function is_dev()
{
    return _env('APP_ENV') === "development";
}

function is_testing()
{
    return _env('APP_ENV') === "testing";
}

function is_prod()
{
    return _env('APP_ENV') === "production";
}