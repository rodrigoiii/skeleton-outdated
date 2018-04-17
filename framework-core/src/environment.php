<?php

$is_own_server = count(glob($_SERVER['DOCUMENT_ROOT'] . "/.env")) === 0;

$root = PHP_SAPI === "cli" ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'] . ($is_own_server ? "/.." : "");
$str = !empty($str) ? "/{$str}" : "";
$base_path = $root . $str;

/*
 |-----------------------------
 | Load application environment
 |-----------------------------
 */
$dotenv = new \Dotenv\Dotenv($base_path);
$dotenv->overload();
$dotenv->required([
    # application configuration
    'APP_NAME', 'APP_NAMESPACE', 'APP_ENV', 'APP_KEY',

    # database configuration
    'DB_HOSTNAME', 'DB_PORT', 'DB_USERNAME', 'DB_PASSWORD', 'DB_NAME',

    # debugging
    'DEBUG_ON', 'DEBUG_BAR_ON',

    # application mode
    'WEB_MODE',

    # flag whether use dist or not
    'USE_DIST'
]);