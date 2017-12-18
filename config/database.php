<?php

return [
	'connections' => [
		'mysql' => [
			'host' => _env('DB_HOSTNAME', "localhost"),
			'port' => _env('DB_PORT', "3306"),
			'username' => _env('DB_USERNAME', "root"),
			'password' => _env('DB_PASSWORD', ""),
			'database' => _env('DB_DATABASE', ""),

			'driver'    => "mysql",
			'charset'   => "utf8",
			'collation' => "utf8_unicode_ci",
			'prefix'    => "",

			'strict' => false,
			'engine' => null
		]
	]
];
