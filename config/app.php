<?php

return [
	'name'        => _env('APP_NAME', "App Name"),
	'namespace'   => _env('APP_NAMESPACE', "App"),
	'environment' => _env('APP_ENV', "development"), // Options: development, production, testing
	'key'         => _env('APP_KEY'),

	'web_mode' => _env('WEB_MODE'),

	'cache' => false,

	'route_on' => true,

	'uploads_path' => "uploads",

	// these path are relative on 'resources/views' path
	'error_pages_path' => [
		"500" => "templates/error-pages/page-500.twig",
		"405" => "templates/error-pages/page-405.twig",
		"404" => "templates/error-pages/page-404.twig",
		"403" => "templates/error-pages/page-403.twig",
		"under-construction" => "templates/error-pages/page-under-construction.twig",
	],

	'debug' => filter_var(_env('DEBUG_ON', true), FILTER_VALIDATE_BOOLEAN),

	// tracy debug bar
	'debug_bar' => filter_var(_env('DEBUG_BAR_ON', true), FILTER_VALIDATE_BOOLEAN)
];