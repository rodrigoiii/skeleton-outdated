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
 * Check if the hostname is localhost
 * @return boolean 		true if localhost, otherwise false
 */
function is_local()
{
	return getenv("APP_ENV") === "local";
}

/**
 * Check if the hostname is staging
 * @return boolean 		true if staging, otherwise false
 */
function is_staging()
{
	return getenv("APP_ENV") === "staging";
}

/**
 * Check if production mode
 * @return boolean 		true if production mode, otherwise false
 */
function is_prod()
{
	return getenv("APP_ENV") === "production";
}

/**
 * Check if development mode
 * @return boolean 		true if development mode, otherwise false
 */
function is_dev()
{
	return getenv('DEBUG') == "true";
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
