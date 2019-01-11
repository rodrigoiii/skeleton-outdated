<?php

return [
    'name'            => app_env('APP_NAME', "App Name"),
    'app_environment' => app_env('APP_ENV', "development"),
    'key'             => app_env('APP_KEY'),
    'mode'            => filter_var(app_env('APP_MODE', false), FILTER_VALIDATE_BOOLEAN),
    'use_dist'        => filter_var(app_env('USE_DIST', false), FILTER_VALIDATE_BOOLEAN),
    'debug'           => filter_var(app_env('DEBUG_ON', false), FILTER_VALIDATE_BOOLEAN),

    # Default timezone
    'default_timezone' => "Asia/Manila",

    # Determine route before application middleware
    'route_on' => true,

    # Aliases
    'aliases' => [
        'Log'     => SkeletonCore\Utilities\Log::class,
        'Session' => SkeletonCore\Utilities\Session::class,
        'DB'      => Illuminate\Database\Capsule\Manager::class
    ],

    'controller_extension' => [
        'SkeletonAuth\\' => "SkeletonAuth/Controllers" // values relative in 'app/' folder
    ],

    'middleware_extension' => [
        'SkeletonAuth\\' => "SkeletonAuth/Middlewares" // values relative in 'app/' folder
    ],

    'request_extension' => [
        'SkeletonAuth\\' => "SkeletonAuth/Requests" // values relative in 'app/' folder
    ],

    // values relative in 'app/' folder
    'validation_extension' => ["SkeletonAuth/Validation/Rules"]
];
