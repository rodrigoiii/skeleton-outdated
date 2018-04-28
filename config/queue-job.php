<?php

return [
    'pheanstalk' => [
        'host' => "127.0.0.1", // this is host
        'job_namespace' => _env('APP_NAMESPACE', "App") . "\Jobs" // this is where your jobs location
    ]
];