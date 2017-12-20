<?php

/**
 * is_ajax check if request is via ajax
 * @return boolean 		true if via ajax, otherwise false
 */
function is_ajax()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

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
