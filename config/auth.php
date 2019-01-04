<?php

return [
    'registration' => [
        // 'is_verification_enabled' => false,
        'is_verification_enabled' => true, // remove this after

        'is_log_in_after_register' => false,
        'token_expiration' => 60 * 60 * 5 // 5 hours
    ],

    'reset_password' => [
        'token_expiration' => 60 * 30 // 30 minutes
    ]
];
