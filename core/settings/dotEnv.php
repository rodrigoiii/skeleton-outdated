<?php

/*
 |-----------------------------
 | Setup for 'DotEnv'
 |-----------------------------
 */
$dotenv = new \Dotenv\Dotenv(base_path());
$dotenv->overload();
$dotenv->required([
	# require app configuration
	'APP_NAME', 'APP_URL', 'APP_ENV', 'APP_NAMESPACE', 'APP_KEY',

	# require database configuration
	'DB_HOSTNAME', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE',

	# require development mode
	'DEBUG'
]);