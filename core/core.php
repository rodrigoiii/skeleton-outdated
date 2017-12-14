<?php

$functions = glob(__DIR__ . "/functions/*.php");
foreach ($functions as $fn) {
	require_once $fn;
}

$functions = glob(__DIR__ . "/../functions/*.php");
foreach ($functions as $fn) {
	require_once $fn;
}