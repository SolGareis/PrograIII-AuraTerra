<?php
// 1. Cargamos el autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Cargamos la base de datos (Capsule)
require_once __DIR__ . '/../config/database.php';

// 3. Arrancamos el Router
$router = new \App\Router();
$router->run();