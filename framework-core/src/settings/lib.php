<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Respect\Validation\Validator as v;

/*
 |-----------------------------
 | Setup for 'illuminate/database'
 |-----------------------------
 */
$capsule = new Capsule;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$capsule::connection()->enableQueryLog();

/*
 |-----------------------------
 | Setup for 'respect/validation'
 |-----------------------------
 */
v::with("Validation\\Rules\\");
v::with(config('app.namespace') . "\\Validation\\Rules\\");

/*
 |-----------------------------
 | Enable tracy debug bar
 |-----------------------------
 */
if (!is_prod() && config('debug-bar.enabled'))
{
    Tracy\Debugger::enable(Tracy\Debugger::DEVELOPMENT, storage_path('logs'));
    Tracy\Debugger::timer();
}