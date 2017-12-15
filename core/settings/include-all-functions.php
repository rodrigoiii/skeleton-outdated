<?php

$functions = glob(core_path("functions/*.php"));
foreach ($functions as $fn) {
	require_once $fn;
}

$functions = glob(base_path("functions/*.php"));
foreach ($functions as $fn) {
	require_once $fn;
}