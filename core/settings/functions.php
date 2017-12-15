<?php

# functions
$functions = glob(__DIR__ . "/../functions/*.php");
foreach ($functions as $fn) {
	require_once $fn;
}

$functions = glob(__DIR__ . "/../../functions/*.php");
foreach ($functions as $fn) {
	require_once $fn;
}

class Functions
{
	public function __call($method, $args)
	{
		return call_user_func_array($method, $args);
	}
}