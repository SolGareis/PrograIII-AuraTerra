<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * Clase Item
 * Esta clase representa la entidad de la tabla 'items' en la base de datos.
 * Al extender de Model (Eloquent), obtenemos métodos como all(), find(), create(), etc.
 */
class Item extends Model
{
    // 1. Definimos el nombre exacto de la tabla en tu base de datos (PHPMyAdmin)
    protected $table = 'items';

    // 2. Definimos qué campos permitimos que se llenen mediante un formulario o JSON.
    // Esto es fundamental para que el método Item::create() funcione sin errores de seguridad.
    protected $fillable = ['name', 'quantity', 'price'];

    // 3. Desactivamos los timestamps (created_at y updated_at).
    // Por defecto, Eloquent busca estas columnas. Si no las creaste en tu tabla, 
    // debés poner esto en false para que no arroje error al insertar datos.
    public $timestamps = true;
}