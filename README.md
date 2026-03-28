
📝Informe de Avances - Clase 2 - GAREIS, SOLEDAD

✅ Hecho
Estructura MVC inicial: Organización de carpetas en public/, src/ y config/.

Router Manual: Implementación de una clase Router en PHP que procesa la REQUEST_URI y el METHOD.

Endpoint de Salud: Funcionamiento del endpoint /health que devuelve un JSON válido con el estado del servidor y la versión de PHP.

Ruteo con .htaccess: Configuración de Apache para redirigir todas las peticiones al index.php central.

Control de Versiones: Inicialización de Git con commit descriptivo.


⏳ Falta
Archivo .gitignore: Todavía no configuré qué archivos ignorar (como carpetas de sistema o temporales).

Modularización de Rutas: Actualmente las rutas están escritas directamente dentro del Router; falta moverlas al archivo config/routes.php.

Pruebas en otros entornos: Solo se probó en Windows con XAMPP; falta verificar el comportamiento en sistemas Linux/Docker.


🚧 Bloqueo
Configuración de Apache (mod_rewrite): Me trabó bastante entender por qué el servidor ignoraba el archivo .htaccess al principio 

![Captura de pantalla de la Home](Imagenes/AgroSense_public.jpeg)
![Captura de pantalla de la Home](Imagenes/AgroSense_loquesea.jpeg)
![Captura de pantalla de la Home](Imagenes/AgroSense_health.jpeg)
![Captura de pantalla de la Home](Imagenes/CommitGit.png)
