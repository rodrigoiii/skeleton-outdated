<?php

# WelcomeController
$container['WelcomeController'] = function ($c)
{
    return new App\Http\Controllers\WelcomeController($c);
};

