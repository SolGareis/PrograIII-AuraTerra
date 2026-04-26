<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'port' => '3307',
    'database'  => 'auraterra',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// ESTO ES LO MÁS IMPORTANTE:
$capsule->setAsGlobal(); 
$capsule->bootEloquent();