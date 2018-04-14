<?php

return [
    'driver' => _env('MAIL_DRIVER', "smtp"),
    'host' => _env("MAIL_HOST", "smtp.mailtrap.io"),
    'port' => _env("MAIL_PORT", 2525),
    'username' => _env("MAIL_USERNAME"),
    'password' => _env("MAIL_PASSWORD"),

    'options' => [
        # email templates directory
        'view_path' => resources_path("views/emails"),

        # cache directory or false to disable
        'cache' => is_prod() ? storage_path("cache/emails") : false,

        # debugging for notification-slim library
        'debug' => filter_var(_env("MAIL_DEBUG", false), FILTER_VALIDATE_BOOLEAN)
    ]
];