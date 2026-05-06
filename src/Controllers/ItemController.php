<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\Item;

class ItemController 
{
    // Lógica para Listar (GET)
    public function index($searchTerm = null) {
        try {
            if ($searchTerm) {
                $items = Item::where('name', 'LIKE', "%{$searchTerm}%")->get();
            } else {
                $items = Item::all();
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(["ok" => true, "items" => $items], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["ok" => false, "error" => $e->getMessage()]);
        }
    }

    // Lógica para Guardar (POST)
    public function store(array $data) {
        $name = isset($data['name']) ? trim($data['name']) : '';
        $quantity = $data['qty'] ?? ''; 

        $errors = [];
        if ($name === '' || strlen($name) < 3) {
            $errors[] = "El nombre es obligatorio y debe tener al menos 3 caracteres.";
        } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $name)) {
            $errors[] = "El nombre solo puede contener letras y espacios.";
        }

        if (!is_numeric($quantity) || (int)$quantity < 0 || (int)$quantity > 9999) {
            $errors[] = "La cantidad (qty) debe ser un número entero entre 0 y 9999.";
        }

        if (count($errors) > 0) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(["ok" => false, "errors" => $errors], JSON_UNESCAPED_UNICODE);
        } else {
            try {
                $item = Item::create([
                    'name'     => $name,
                    'quantity' => (int)$quantity,
                    'price'    => $data['price'] ?? 0
                ]);
                http_response_code(201);
                header('Content-Type: application/json');
                echo json_encode(["ok" => true, "item" => $item], JSON_UNESCAPED_UNICODE);
            } catch (\Exception $e) {
                http_response_code(500);
                echo json_encode(["ok" => false, "error" => "Error al guardar en la base de datos."]);
            }
        }
    }
}