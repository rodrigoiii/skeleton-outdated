<?php

/*
 |-----------------------------
 | Setup for 'illuminate/database'
 |-----------------------------
 */
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$capsule::connection()->enableQueryLog();

/*
 |-----------------------------
 | Setup for 'runcmf/runtracy' debug bar
 |-----------------------------
 */
use Tracy\Debugger;
Debugger::enable(config('app.debug_bar') ? Debugger::DEVELOPMENT : Debugger::PRODUCTION, storage_path("logs"));
Debugger::timer();

/*
 |-----------------------------
 | Setup for 'respect/validation'
 |-----------------------------
 */
use Respect\Validation\Validator as v;
v::with(config('app.namespace') . '\\Validation\\Rules\\');