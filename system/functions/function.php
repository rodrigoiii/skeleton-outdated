<?php

# functions
$functions = glob(__DIR__ . "/../functions/*.php");
foreach ($functions as $fn) {
    if (basename($fn, ".php") !== "function")
    {
        require_once $fn;
    }
}