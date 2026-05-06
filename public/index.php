<?php
// Subimos un nivel (../) para encontrar la raíz
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

$router = new \App\Router();
$router->run();