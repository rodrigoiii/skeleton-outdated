<?php

/*
 |-----------------------------
 | Setup for 'Illuminate Database'
 |-----------------------------
 */
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$capsule::connection()->enableQueryLog();