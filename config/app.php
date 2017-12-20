<?php

return [
	'name'      => _env('APP_NAME', "App Name"),
	'namespace' => _env('APP_NAMESPACE', "App"),
	'key'       => _env('APP_KEY'),

	'cache' => false,

	'route_on' => true,

	'upload-path' => public_path("uploads"),

	// these path are relative on 'resources/views' path
	'error-pages-path' => [
		"500" => "templates/error-pages/page-500.twig",
		"405" => "templates/error-pages/page-405.twig",
		"404" => "templates/error-pages/page-404.twig",
		"403" => "templates/error-pages/page-403.twig",
		"under-construction" => "templates/error-pages/page-under-construction.twig",
	],

	'debug' => _env('DEBUG_ON', true),

	// tracy debug bar
	'debug-bar' => _env('DEBUG_BAR_ON', true)
];