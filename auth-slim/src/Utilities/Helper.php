<?php

namespace AuthSlim\Utilities;

class Helper
{
    public static function getUserIp()
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

    public static function strTitle($str, $char = "_")
    {
        $title_array = array_map("ucfirst", explode($char, $str));
        return implode(" ", $title_array);
    }

    public function baseUrl($str_added = "")
    {
        $str_added = !empty($str_added) ? "/{$str_added}" : "";
        return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . $str_added;
    }
}