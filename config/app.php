<?php

return [
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

	// tracy debug bar
	'debug-bar' => true
];