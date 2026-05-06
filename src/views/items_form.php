<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/AuraTerra/public/style.css"> <!-- Busca las reglas de diseño en esta ruta-->
    <title>Nuevo Item</title>
</head>
<body>
    <div class="container"> <!-- contenedor que agrupa todos los elementos del formulario-->
        <h1>Crear Nuevo Item</h1>
        <form method="POST" action="/AuraTerra/public/items" novalidate> <!-- navegador no usa sus validaciones automáticas de HTML5, nosotros manejamos los errores personalizados-->
            <label>Nombre del Item:
                <input type="text" 
                    name="name" 
                    pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+" 
                    title="Solo se permiten letras" 
                required>
            </label>

            <label>Cantidad:
                <input type="int" name="qty" min="1" required>
            </label>

            <div id="message"></div> <!-- espacio vacio y reserado que permite escribri cx adentro. exito o error-->

            <button type="submit">Crear Item</button>
        </form>
        <div class="search-container">
        <input type="text" id="search-input" placeholder="Buscar por nombre...">
        <button type="button" id="search-button">Buscar</button>
        </div>
        <div id="loading-indicator" style="display: none;">Cargando productos...</div>
        <hr>
        <h3>Listado de Items Actuales</h3>
        <div id="items-list">
    <!-- Acá el JS va a dibujar la lista sola -->
    </div>

    <script src="/AuraTerra/public/main.js"></script> <!-- ejecuta las isnt que estan en este archivo. Formulario se envía mediante fetch sin recargar la página-->
</body>
</html>
