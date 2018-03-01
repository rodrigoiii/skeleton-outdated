<?php

return [
    'name'        => _env('APP_NAME', "App Name"),
    'namespace'   => _env('APP_NAMESPACE', "App"),
    'environment' => _env('APP_ENV', "development"), // Options: development, production, testing
    'key'         => _env('APP_KEY'),

    # default timezone
    'default_timezone' => "Asia/Manila",

    # Where upload will be stored. The location is `public/uploads`
    'uploads_path' => "uploads",

    # Determine route before application middleware
    'route_on' => true,

    # Application either UP or DOWN(under construction)
    'web_mode' => _env('WEB_MODE'),

    # Debug mode
    'debug' => filter_var(_env('DEBUG_ON', true), FILTER_VALIDATE_BOOLEAN),

    # Aliases
    'aliases' => [
        // Framework Utilities
        'Log'     => Framework\Utilities\Log::class,
        'Session' => Framework\Utilities\Session::class,
    ]
];