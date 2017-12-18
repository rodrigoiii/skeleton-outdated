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
 * Check if file_path is in uploads folder
 * @param  [string]  $file_path [the path of file]
 * @return boolean            [true if file is in upload folders, otherwise false]
 */
function isInUpload($file_path)
{
	return strpos($file_path, config('app.upload-path.base')) === 0;
}

/**
 * Base path of the Url
 * @return [string] [Base of the url]
 */
function base_url($str = "")
{
	if (!empty($str))
	{
		$str = "/{$str}";
	}

	return _env('APP_URL') . $str;
}