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

/**
 * Generate token(hash string)
 * @param  [string] $string [Mix in uniqid() and salt]
 * @return [string]         [hash string]
 */
function generate_token($string)
{
    $salt = _env('APP_KEY');
    return md5(uniqid() . $string . $salt );
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
	echo "<pre>";
	d($to_be_print);
	die;
}

/**
 * Make the slug friendly text
 * @param  [string] $text [to be slugify]
 * @return [string]       [slugify text]
 */
function slugify($text)
{
	// replace non letter or digits by -
	$text = preg_replace('~[^\pL\d]+~u', '-', $text);

	// transliterate
	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);

	// trim
	$text = trim($text, '-');

	// remove duplicate -
	$text = preg_replace('~-+~', '-', $text);

	// lowercase
	$text = strtolower($text);

	if (empty($text)) {
		return 'n-a';
	}

	return $text;
}

function getUserIP()
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

function isActive($pattern, $text)
{
	$pattern = preg_quote($pattern, "/");
	return preg_match("/^{$pattern}$/", $_SERVER['REQUEST_URI']) === 1 ? $text : "";
}

function is_valid_date($date, $format = "Y-m-d")
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function is_philippine_mobile_number($number)
{
	return preg_match("/^(09|\+639)\d{9}$/", $number);
}

function str_title($str, $char = "_")
{
	$new_word = "";
	for ($i=0; $i < strlen($str); $i++) {
		if ($str[$i] === $char)
		{
			try {
				$new_word .= " " . strtoupper($str[++$i]);
			} catch (Exception $e) {
			}
		}
		else
		{
			$new_word .= $str[$i];
		}
	}

	return ucfirst($new_word);
}

function get_exploded_date(array $date, $separator="-", $index=0)
{
	$exploded_date = [];
	foreach ($date as $d) {
		$explode_year = explode($separator, $d);
		$exploded_date[] = $explode_year[$index];
	}

	return $exploded_date;
}