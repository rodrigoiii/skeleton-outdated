#!/usr/bin/env php
<?php

if (PHP_SAPI !== "cli") die; // abort if the usage not via command line

# composer autoload
require __DIR__ . "/vendor/autoload.php";

$appCli = new SkeletonCore\AppCli;
$appCli->run();
