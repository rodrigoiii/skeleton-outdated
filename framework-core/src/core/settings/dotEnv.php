<?php

# Load environment
$dotenv = new \Dotenv\Dotenv(base_path());
$dotenv->overload();
$dotenv->required(config('environment.required'));