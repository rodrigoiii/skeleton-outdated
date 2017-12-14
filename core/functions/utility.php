<?php

/**
 * Access the configuration at the config folder.
 * @param  [string] $string [path with dot separated]
 * @return [mix]         [return specify value according to paramater]
 */
function config($string)
{
	$temp = explode(".", $string);

	$filename = array_shift($temp);
	$keys = $temp;

	$content = include config_path("{$filename}.php");

	$active = $content;
	foreach ($keys as $key) {
		$active = $active[$key];
	}
	return $active;
}

/**
 * Returning the absolute path with string provided
 * @param  [string] $string [string provided]
 * @return [string]         [absolute path + string provided]
 */
function path_for ($string)
{
	if ( dirname($_SERVER['PHP_SELF']) === "\\" )
	{
		return ($string === "") ? $string : "/" . $string;
	}
	return dirname($_SERVER['PHP_SELF']) . "/" . $string;
}

/**
 * [Check if the host to be use is own]
 * @return boolean
 */
function isOwnServer()
{
	return count(glob($_SERVER['DOCUMENT_ROOT'] . "/.env")) === 0;
}

/**
 * [Check if the host to be use is shared]
 * @return boolean
 */
function isSharedServer()
{
	return count(glob($_SERVER['DOCUMENT_ROOT'] . "/.env")) === 1;
}

/**
 * Timthumb is image resizer
 * @param  [string] $file    [image path]
 * @param  [string] $options [options of timthumb]
 * @return [string]          [absolute path with thimthumb, file and options]
 */
function timthumb ($file, $options)
{
	$path = "api/helpers/timthumb?src=".base_url()."/".$file."$options";

	return isSharedServer() ? "public/{$path}" : $path;
}