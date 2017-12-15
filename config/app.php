<?php

return [
	'route_on' => true,

	'upload-path' => public_path("uploads"),

	'error-pages-path' => [
		"500" => resources_path("views/templates/error-pages/page-500.twig"),
		"405" => resources_path("views/templates/error-pages/page-405.twig"),
		"404" => resources_path("views/templates/error-pages/page-404.twig"),
		"403" => resources_path("views/templates/error-pages/page-403.twig"),
		"under-construction" => resources_path("views/templates/error-pages/page-under-construction.twig"),
	]
];