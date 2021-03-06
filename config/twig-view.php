<?php

return [
    # cache directory or false to disable
    'cache' => is_prod() ? storage_path("cache/views") : false,

    # error pages path, relative in resources/views path
    'error_pages' => [
        "500" => "templates/error-pages/page-500.twig",
        "405" => "templates/error-pages/page-405.twig",
        "404" => "templates/error-pages/page-404.twig",
        "403" => "templates/error-pages/page-403.twig",
        "under-construction" => "templates/error-pages/page-under-construction.twig",
    ]
];
