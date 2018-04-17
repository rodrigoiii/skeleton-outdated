<?php

// composer autoload
require "../vendor/autoload.php";

$dotenv = new \Dotenv\Dotenv(base_path(), ".env.testing");
$dotenv->overload();

$app = include base_path("bootstrap/app.php");