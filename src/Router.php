<?php declare(strict_types=1);
namespace App;

use App\Models\Item;

class Router 
{
    public function run() {
        // 1. Captura de método y ruta
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = (string)parse_url($requestUri, PHP_URL_PATH);

        // 2. Limpieza de prefijos de carpeta (para localhost/AuraTerra/public/...)
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $scriptDir = str_replace('\\', '/', dirname($scriptName));
        if (strpos($path, $scriptDir) === 0) {
            $path = substr($path, strlen($scriptDir));
        }
        $path = '/' . ltrim($path, '/');

        // ---------------------------------------------------------
        // RUTA GET /items (LISTAR REGISTROS)
        // ---------------------------------------------------------
        if ($method === 'GET' && ($path === '/items' || $path === '/items/')) {
            try {
                $items = Item::all();
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($items, JSON_UNESCAPED_UNICODE);
            } catch (\Exception $e) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
            }
            exit;
        }

        // ---------------------------------------------------------
        // RUTA POST /items (GUARDAR REGISTRO)
        // ---------------------------------------------------------
        if ($method === 'POST' && ($path === '/items' || $path === '/items/')) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : '';
            $errors = [];
            
            // Validaciones (tus reglas de negocio)
            if ($name === '') {
                $errors[] = "El nombre es obligatorio.";
            } elseif (strlen($name) < 3) {
                $errors[] = "El nombre debe tener al menos 3 caracteres.";
            }

            if (!ctype_digit($quantity) || (int)$quantity <= 0) {
                $errors[] = "La cantidad debe ser un número entero mayor a cero.";
            }

            if (count($errors) > 0) {
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode(['ok' => false, 'errors' => $errors], JSON_UNESCAPED_UNICODE);
            } else {
                $data = [
                'name'     => $_POST['name'] ?? '',
                'quantity' => $_POST['qty'] ?? 0,    // Usamos 'qty' que viene del POST
                'price'    => $_POST['price'] ?? null
                ];
                
                try {
                    $item = Item::create([
                    'name'     => $data['name'],
                    'quantity' => $data['quantity'],
                    'price'    => $data['price'] ?? null
                ]);
                    http_response_code(201);
                    header('Content-Type: application/json');
                    echo json_encode(['ok' => true, 'item' => $item], JSON_UNESCAPED_UNICODE);
                } catch (\Exception $e) {
                    http_response_code(500);
                    echo json_encode(['error' => 'No se pudo guardar: ' . $e->getMessage()]);
                }
            }
            exit;
        }

        // ---------------------------------------------------------
        // RUTA 404 (SI NADA COINCIDE)
        // ---------------------------------------------------------
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Ruta no encontrada', 'path' => $path]);
        exit;
    }
}