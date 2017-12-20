<?php

function base_path($str = "")
{
	$root = PHP_SAPI === "cli" ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'] . (isOwnServer() ? "/.." : "");
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

function core_path($str = "")
{
	$str = !empty($str) ? "/{$str}" : "";
	return base_path("core") . $str;
}

function public_path($str = "")
{
	$str = !empty($str) ? "/{$str}" : "";
	return base_path("public") . $str;
}

function resources_path($str = "")
{
	$str = !empty($str) ? "/{$str}" : "";
	return base_path("resources") . $str;
}

function settings_path($str = "")
{
	$str = !empty($str) ? "/{$str}" : "";
	return base_path("settings") . $str;
}

function storage_path($str = "")
{
	$str = !empty($str) ? "/{$str}" : "";
	return base_path("storage") . $str;
}