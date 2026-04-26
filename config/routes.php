<?php

use App\Models\Item;

//estructura que va a contener toda la lógica de navegación.
class Router {
    //Cuando el index.php llama a run(), se aprieta el botón de "encendido" del ruteo.
    public function run() {
        $method = $_SERVER['REQUEST_METHOD']; //¿Cómo vino el pedido?(GET/POST)
        $requestUri = $_SERVER['REQUEST_URI']; //Captura la URL completa que escribió el usuario

        // Limpiamos la ruta para que solo quede lo que viene después de /public
        $path = parse_url($requestUri, PHP_URL_PATH); //para que solo vea el healt
        $path = str_replace('/AuraTerra/public', '', $path); //No me importa en qué edificio estoy, solo quiero saber a qué oficina vOY
        if ($path === '' || $path === false) { $path = '/'; } // pagina de inicio /

        // Ruta de salud
        if ($method === 'GET' && $path === '/health') {
            header('Content-Type: application/json');
            echo json_encode([
                "status" => "ok",
                "timestamp" => date("Y-m-d H:i:s"),
                "php_version" => phpversion(),
                "server" => "Apache/XAMPP (AuraTerra MVC)"
            ], JSON_PRETTY_PRINT);
            exit;
        }

        // Ruta por defecto
        if ($path === '/') {
            echo "Bienvenido a la API de AuraTerra. Probá /health";
            exit;
        }

        // Si no existe la ruta
        http_response_code(404);
        echo "404 - Ruta no encontrada: " . $path;

        if ($method === 'GET' && $path === '/items/new') {
            include __DIR__ . '/views/items_form.php';
            exit;
        }
    }
}