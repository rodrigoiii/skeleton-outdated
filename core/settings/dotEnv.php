<?php

/*
 |-----------------------------
 | Setup for 'DotEnv'
 |-----------------------------
 */
$dotenv = new \Dotenv\Dotenv(base_path(), (is_testing() ? ".env.testing" : ".env"));
$dotenv->overload();
$dotenv->required(config('framework.required_environment'));