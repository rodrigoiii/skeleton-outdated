<?php

function base_path($str = "")
{
	$root = PHP_SAPI === "cli" ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'] . (is_own_server() ? "/.." : "");
	$str = !empty($str) ? "/{$str}" : "";
	return $root . $str;
}

function app_path($str = "")
{
	$str = !empty($str) ? "/{$str}" : "";
	return base_path("app") . $str;
}

function config_path($str = "")
{
	$str = !empty($str) ? "/{$str}" : "";
	return base_path("config") . $str;
}

function resources_path($str = "")
{
	$str = !empty($str) ? "/{$str}" : "";
	return base_path("resources") . $str;
}

function storage_path($str = "")
{
	$str = !empty($str) ? "/{$str}" : "";
	return base_path("storage") . $str;
}

function system_path($str = "")
{
	$str = !empty($str) ? "/{$str}" : "";
	return base_path("system") . $str;
}