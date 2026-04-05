<?php
declare(strict_types=1);

class Router 
{
    public function run() {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET'; //Metodo HTTP
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/'; //URI pedida
        $path = (string)parse_url($requestUri, PHP_URL_PATH); // Quitar query string

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? ''; //Normalizar posibles bases
        $scriptDir  = str_replace('\\', '/', dirname($scriptName));

        if ($scriptDir !== '/' && strpos($path, $scriptDir) === 0) {
            $path = substr($path, strlen($scriptDir));
        } else {
            $path = preg_replace('#^/public#', '', $path);
        } //Si la URL contiene el directorio del script, lo quitamos

        $path = '/' . ltrim((string)$path, '/'); //asegurar formato
        if ($path === '//') { $path = '/'; }

        // Ruta GET
        if ($method === 'GET' && $path === '/health') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'ok', 'timestamp' => date('Y-m-d H:i:s')], JSON_PRETTY_PRINT);
            exit;
        }

        // Ruta base opcional
        if ($method === 'GET' && ($path === '/' || $path === '')) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['message' => 'Bienvenido a AuraTerra.'], JSON_PRETTY_PRINT);
            exit;
        }

        // Ruta para mostrar el formulario de items - exactamente debe decir /items
        if ($method === 'GET' && $path === '/items/new') {
            include __DIR__ . '/../config/views/items_form.php';
            exit;
        }

        // Ruta POST
        if ($method === 'POST' && $path === '/items') {
            $name = isset($_POST['name']) ? trim($_POST['name']) : ''; // isset-pregunta si el dato existe. ? - if cortito, si eciste lo guarda sino deja texto vacio ' '
            $qty = isset($_POST['qty']) ? trim($_POST['qty']) : ''; // trim - borra espacios vacios
            
            $errors = []; //Array para acumular errores

            if ($name === '') {
                $errors[] = 'El campo name es obligatorio';
            } elseif (strlen($name) < 3) {
                $errors[] = 'El campo name debe tener al menos 3 caracteres';
            }

            if ($qty === '') {
                $errors[] = 'El campo qty es obligatorio';
            } elseif (!ctype_digit($qty)) {
                $errors[] = 'El campo qty debe ser un número entero';
            } elseif ((int)$qty <= 0) {
                $errors[] = 'El campo qty debe ser mayor que 0';
            }

            header('Content-Type: application/json; charset=utf-8'); // Decidir respuesta según si hay errores

            if (count($errors) > 0) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'errors' => $errors], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(201);
                echo json_encode([
                    'ok' => true,
                    'item' => ['name' => $name, 'qty' => (int)$qty]
                ], JSON_UNESCAPED_UNICODE);
            }
            exit;
        }

        // 404 si nada coincidio
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(404);
        echo json_encode(['error' => 'Not Found', 'path' => $path, 'method' => $method], JSON_PRETTY_PRINT);
    }
}