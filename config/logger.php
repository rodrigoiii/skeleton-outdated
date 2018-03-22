<?php

return [
    'monolog' => [
        'name' => _env('APP_NAME', "App Name"),
        'level' => Monolog\Logger::DEBUG,
        'path' => storage_path("logs/app.log")
    ]
];