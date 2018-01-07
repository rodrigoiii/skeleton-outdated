<?php

/*
 |-----------------------------
 | Setup for 'DotEnv'
 |-----------------------------
 */
$dotenv = new \Dotenv\Dotenv(base_path(), (is_testing() ? ".env.testing" : ".env"));
$dotenv->overload();
$dotenv->required([
	# require app configuration
	'APP_NAME', 'APP_NAMESPACE', 'APP_KEY',

	# require database configuration
	'DB_HOSTNAME', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE',

	# require development mode
	'DEBUG_ON', 'DEBUG_BAR_ON'
]);