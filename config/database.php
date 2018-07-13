<?php

return [
    'database_connection' => [
        'mysql' => [
            'host' => app_env('DB_HOSTNAME', "localhost"),
            'port' => app_env('DB_PORT', "3306"),
            'username' => app_env('DB_USERNAME', "root"),
            'password' => app_env('DB_PASSWORD'),
            'database' => app_env('DB_NAME'),

            'driver'    => "mysql",
            'charset'   => "utf8",
            'collation' => "utf8_unicode_ci",
            'prefix'    => "",

            'strict' => true,
            'engine' => null
        ]
    ]
];
