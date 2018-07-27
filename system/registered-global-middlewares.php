<?php

# register your custom middleware globally.
$app->add(new PageBlocker\Middleware([
    'db-config' => [
        'host' => app_env('DB_HOSTNAME', "localhost"),
        'username' => app_env('DB_USERNAME', "root"),
        'password' => app_env('DB_PASSWORD'),
        'database' => app_env('DB_NAME'),
    ],
    'table' => "page_blocker",
    'glob-url' => "/block",

    'unauthorizedCallback' => function() {
        exit("Unauthorized");
    }
]));
