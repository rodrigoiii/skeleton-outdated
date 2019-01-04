<?php

return [
    'login' => [
        'enabled' => true
    ],

    'register' => [
        'enabled' => true,
        // 'is_verification_enabled' => false,
        'is_verification_enabled' => true, // remove this after

        'is_log_in_after_register' => false,
        'token_expiration' => 60 * 60 * 5 // 5 hours
    ],

    'forgot_password' => [
        'enabled' => true
    ],

    'reset_password' => [
        'enabled' => true,
        'token_expiration' => 60 * 30 // 30 minutes
    ],

    'change_password' => [
        'enabled' => true
    ]
];
