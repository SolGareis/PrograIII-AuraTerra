<?php declare(strict_types=1);

namespace App;

use App\Controllers\ItemController;

class Router 
{
    public function run() {
        // 1. CAPTURA DE MÉTODO Y URI
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        // 2. LIMPIEZA DE RUTA
        $path = (string)parse_url($requestUri, PHP_URL_PATH);
        // 3. ADAPTACIÓN A LA ESTRUCTURA (htaccess en la raíz)
        $path = str_replace(['/AuraTerra/public', '/AuraTerra'], '', $path);
        // 4. NORMALIZACIÓN
        if ($path === '' || $path === '/index.php' || $path === '/public' || $path === '/public/index.php') {
            $path = '/';
        } else {
            $path = str_replace('/public', '', $path);
            $path = '/' . rtrim(ltrim($path, '/'), '/');
        }

        $controller = new ItemController();

        // RUTA RAÍZ: MAPA DE LA API
        if ($path === '/') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                "proyecto" => "AuraTerra API",
                "version" => "1.0.0",
                "descripcion" => "Sistema de gestión agrícola para el parcial de Programación III",
                "rutas_disponibles" => [
                    [
                        "url" => "/items",
                        "metodo" => "GET",
                        "descripcion" => "Devuelve un listado completo de todos los ítems en formato JSON."
                    ],
                    [
                        "url" => "/items/nuevo",
                        "metodo" => "GET",
                        "descripcion" => "Muestra el formulario HTML interactivo para crear un nuevo registro."
                    ],
                    [
                        "url" => "/items",
                        "metodo" => "POST",
                        "descripcion" => "Recibe datos en formato JSON RAW y los guarda en la base de datos mediante Eloquent."
                    ]
                ],
                "autor" => "Gareis, Soledad" 
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit;
        }

        // LISTAR ITEMS (Delegamos al Controlador)
        if ($method === 'GET' && $path === '/items') {
            $searchTerm = $_GET['q'] ?? null;
            $controller->index($searchTerm);
            exit;
        }

        // MOSTRAR FORMULARIO
        if ($method === 'GET' && $path === '/items/nuevo') {
            include __DIR__ . '/views/items_form.php'; 
            exit;
        }

        // GUARDAR ITEM (Delegamos al Controlador)
        if ($method === 'POST' && $path === '/items') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $controller->store($data);
            exit;
        }

        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada"]);
    }
}