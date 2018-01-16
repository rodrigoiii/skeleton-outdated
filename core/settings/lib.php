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
 | Setup for 'respect/validation'
 |-----------------------------
 */
use Respect\Validation\Validator as v;
v::with(config('app.namespace') . '\\Validation\\Rules\\');