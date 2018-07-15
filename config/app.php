<?php

return [
    'name'        => app_env('APP_NAME', "App Name"),
    'namespace'   => app_env('APP_NAMESPACE', "App"),
    'app_environment' => app_env('APP_ENV', "development"),
    'key'         => app_env('APP_KEY'),
    'mode'        => filter_var(app_env('APP_MODE', false), FILTER_VALIDATE_BOOLEAN),
    'use_dist'    => filter_var(app_env('USE_DIST', false), FILTER_VALIDATE_BOOLEAN),
    'debug'       => filter_var(app_env('DEBUG_ON', false), FILTER_VALIDATE_BOOLEAN),

    # Default timezone
    'default_timezone' => "Asia/Manila",

    # Where upload will be stored. The location is `public/uploads`
    'uploads_path' => "uploads",

    # Determine route before application middleware
    'route_on' => true,

    # Aliases
    'aliases' => [
        'Log' => SkeletonCore\Utilities\Log::class,
        'Session' => SkeletonCore\Utilities\Session::class,
        'DB' => Illuminate\Database\Capsule\Manager::class
    ]
];
