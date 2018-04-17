<?php

/*
 |-----------------------------
 | Setup for 'illuminate/database'
 |-----------------------------
 */
$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$capsule::connection()->enableQueryLog();

/*
 |-----------------------------
 | Setup for 'respect/validation'
 |-----------------------------
 */
Respect\Validation\Validator::with("FrameworkCore\\Validation\\Rules\\");
Respect\Validation\Validator::with(config('app.namespace') . "\\Validation\\Rules\\");

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