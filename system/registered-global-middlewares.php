<?php

$uri = "/block";
$config = [
    'db_config' => [
        'host' => app_env('DB_HOSTNAME', "localhost"),
        'username' => app_env('DB_USERNAME', "root"),
        'password' => app_env('DB_PASSWORD'),
        'database' => app_env('DB_NAME'),
    ],
    'table' => "page_blocker"
];
$callback = [
    'unauthorized_callback' => function() {
        exit("Unauthorized");
    }
];

# register your custom middleware globally.
$app->add(new PageBlocker\Middleware("/block", $config, $callback));
