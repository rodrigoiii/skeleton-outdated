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
function is_own_server()
{
	return count(glob($_SERVER['DOCUMENT_ROOT'] . "/.env")) === 0;
}

/**
 * [Check if the host to be use is shared]
 * @return boolean
 */
function is_shared_server()
{
	return count(glob($_SERVER['DOCUMENT_ROOT'] . "/.env")) === 1;
}

/**
 * Check if file_path is in uploads folder
 * @param  [string]  $file_path [the path of file]
 * @return boolean            [true if file is in upload folders, otherwise false]
 */
function is_in_uploads($file_path)
{
	return strpos($file_path, config('app.uploads_path')) === 0;
}

/**
 * Base path of the Url
 * @return [string] [Base of the url]
 */
function base_url($str_added = "")
{
    $str_added = !empty($str_added) ? "/{$str_added}" : "";
    return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . $str_added;
}

/**
 * Generate token(hash string)
 * @param  [string] $string [Mix in uniqid() and salt]
 * @return [string]         [hash string]
 */
function generate_token($string)
{
    $salt = config('app.key');
    return md5(uniqid() . $string . $salt);
}

/**
 * Print pretty
 * @param  any type $to_be_print
 */
function pretty_print($to_be_print)
{
	echo "<pre>";
	var_dump($to_be_print);
	die;
}

/**
 * Show data clear and die
 * @param  any type $to_be_print
 */
function _dd($to_be_print)
{
	d($to_be_print);
	die;
}

/**
 * Get user ip address
 * @return [string] [ip address]
 */
function get_user_ip()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

/**
 * Convert string to title format
 * @param  string $str  The subject
 * @param  string $char The character chain in words
 * @return string       String title format
 */
function str_title($str, $char = "_")
{
    $title_array = array_map("ucfirst", explode($char, $str));
    return implode(" ", $title_array);
}

/**
 * Remove files and it's folder
 * @return string Path name of files
 */
function rmdir_recursion($path)
{
    $files = glob("{$path}/*");
    while ($file = current($files))
    {
        if (is_file($file))
        {
            unlink($file);

            $dir = dirname($file);
            while (count(glob("{$dir}/*")) === 0)
            {
                rmdir($dir);
                $dir = dirname($dir);
            }
        }
        else
        {
            rmdir_recursion($file);
        }

        next($files);
    }
}