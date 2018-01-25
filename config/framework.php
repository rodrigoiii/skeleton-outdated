<?php

return [
    /**
     * Twig View cache
     */
    'cache' => false,

    /**
     * Where upload will be stored. The location is `public/uploads`
     */
    'uploads_path' => "uploads",

    /**
     * Error page templates
     */
    'error_pages_path' => [
        "500" => "templates/error-pages/page-500.twig",
        "405" => "templates/error-pages/page-405.twig",
        "404" => "templates/error-pages/page-404.twig",
        "403" => "templates/error-pages/page-403.twig",
        "under-construction" => "templates/error-pages/page-under-construction.twig",
    ],

    /**
     * Required Environment
     */
    'required_environment' => [
        # application configuration
        'APP_NAME', 'APP_NAMESPACE', 'APP_ENV', 'APP_KEY',

        # database configuration
        'DB_HOSTNAME', 'DB_PORT', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE',

        # debugging
        'DEBUG_ON', 'DEBUG_BAR_ON',

        # application mode
        'WEB_MODE'
    ],

    /**
     * Determine route before application middleware
     */
    'route_on' => true,

    /**
     * Application either UP or DOWN(under construction)
     */
    'web_mode' => _env('WEB_MODE'),

    /**
     * Debugging
     */
    'debug' => filter_var(_env('DEBUG_ON', true), FILTER_VALIDATE_BOOLEAN),
    'debug_bar' => filter_var(_env('DEBUG_BAR_ON', true), FILTER_VALIDATE_BOOLEAN),

    /**
     * Database connection
     */
    'database_connection' => [
        'mysql' => [
            'host' => _env('DB_HOSTNAME', "localhost"),
            'port' => _env('DB_PORT', "3306"),
            'username' => _env('DB_USERNAME', "root"),
            'password' => _env('DB_PASSWORD'),
            'database' => _env('DB_DATABASE'),

            'driver'    => "mysql",
            'charset'   => "utf8",
            'collation' => "utf8_unicode_ci",
            'prefix'    => "",

            'strict' => false,
            'engine' => null
        ]
    ],

    /**
     * Monolog library
     */
    'monolog' => [
        'name' => config('app.name'),
        'level' => Monolog\Logger::DEBUG,
        'path' => storage_path("logs/app.log")
    ],

    /**
     * Tracy Debugbar
     */
    'tracy_debugbar' => [
        'showPhpInfoPanel' => 1,
        'showSlimRouterPanel' => 1,
        'showSlimEnvironmentPanel' => 1,
        'showSlimRequestPanel' => 1,
        'showSlimResponsePanel' => 1,
        'showSlimContainer' => 1,
        'showEloquentORMPanel' => 1,
        'showTwigPanel' => 1,
        'showProfilerPanel' => 0,
        'showVendorVersionsPanel' => 0,
        'showIncludedFiles' => 0,
        'configs' => [
            // XDebugger IDE key
            'XDebugHelperIDEKey' => 'SUBLIME',
            // Disable login (don't ask for credentials, be careful) values ( 1 | 0 )
            'ConsoleNoLogin' => 0,
            // Multi-user credentials values( ['user1' => 'password1', 'user2' => 'password2'] )
            'ConsoleAccounts' => [
                'dev' => '34c6fceca75e456f25e7e99531e2425c6c1de443'// = sha1('dev')
            ],
            // Password hash algorithm (password must be hashed) values('md5', 'sha256' ...)
            'ConsoleHashAlgorithm' => 'sha1',
            // Home directory (multi-user mode supported) values ( var || array )
            // '' || '/tmp' || ['user1' => '/home/user1', 'user2' => '/home/user2']
            'ConsoleHomeDirectory' => "",
            // terminal.js full URI
            'ConsoleTerminalJs' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery.terminal/1.11.3/js/jquery.terminal.js',
            // terminal.css full URI
            'ConsoleTerminalCss' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery.terminal/1.11.3/css/jquery.terminal.css',
            'ProfilerPanel' => [
                // Memory usage 'primaryValue' set as Profiler::enable() or Profiler::enable(1)
                // 'primaryValue' =>                   'effective',    // or 'absolute'
                'show' => [
                    'memoryUsageChart' => 1, // or false
                    'shortProfiles' => false, // or false
                    'timeLines' => false // or false
                ]
            ]
        ]
    ]
];