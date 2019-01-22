<?php

return [
    'login' => [
        'enabled' => true,
        'session_expiration' => 60 * 30 // 30 minutes
    ],

    'register' => [
        'enabled' => true,
        'is_verification_enabled' => false,
        'is_log_in_after_register' => false,
        'token_expiration' => 60 * 30 // 30 minutes
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
    ],

    'account_setting' => [
        'enabled' => true
    ]
];
