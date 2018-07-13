<?php

return [
    'name'        => _env('APP_NAME', "App Name"),
    'namespace'   => _env('APP_NAMESPACE', "App"),
    'environment' => _env('APP_ENV', "development"),
    'key'         => _env('APP_KEY'),
    'mode'        => filter_var(_env('APP_MODE', false), FILTER_VALIDATE_BOOLEAN),
    'use_dist'    => filter_var(_env('USE_DIST', false), FILTER_VALIDATE_BOOLEAN),
    'debug'       => filter_var(_env('DEBUG_ON', false), FILTER_VALIDATE_BOOLEAN),

    # Default timezone
    'default_timezone' => "Asia/Manila",

    # Where upload will be stored. The location is `public/uploads`
    'uploads_path' => "uploads",

    # Determine route before application middleware
    'route_on' => true,

    # Aliases
    'aliases' => [
        'Log'     => FrameworkCore\Utilities\Log::class,
        'Session' => FrameworkCore\Utilities\Session::class,
        'DB' => Illuminate\Database\Capsule\Manager::class
    ]
];
