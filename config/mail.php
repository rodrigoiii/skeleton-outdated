<?php

return [
    'driver' => _env('MAIL_DRIVER', "smtp"),
    'host' => _env('MAIL_HOST', "mailtrap.io"),
    'port' => _env('MAIL_PORT', 2525),
    'username' => _env('MAIL_USERNAME'),
    'password' => _env('MAIL_PASSWORD')
];