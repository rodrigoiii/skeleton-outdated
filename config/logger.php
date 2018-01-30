<?php

return [
    /**
     * Monolog library
     */
    'monolog' => [
        'name' => "Framework",
        'level' => Monolog\Logger::DEBUG,
        'path' => storage_path("logs/app.log")
    ],
];