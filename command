#!/usr/bin/env php
<?php

// composer autoload
require __DIR__ . "/vendor/autoload.php";

(new Framework\CoreCommand)->boot([
    new App\Console\Commands\HelloCommand,
    new App\Console\Commands\A,
]);