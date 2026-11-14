<?php
// conexion.php
// Crea la conexión a la base de datos con PDO.
// Los demás archivos lo incluyen con require_once y usan la variable $conexion.

$host = 'localhost';        // el servidor MySQL está en esta misma computadora
$dbname = 'agencia_dm';  // nombre de la base que creamos en phpMyAdmin
$usuario = 'root';          // usuario por defecto de XAMPP
$password = '';             // en XAMPP, root no tiene contraseña

try { // intentamos conectar; si falla, PHP salta al bloque catch
    // new PDO(...) abre la conexión. charset=utf8mb4 permite acentos y ñ
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $usuario, $password);

    // Si una consulta falla, PDO lanza una excepción (así podemos capturarla con try/catch)
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cada fila llega como array asociativo: $fila['nombre']
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage()); // guarda el error técnico en el log del servidor (el usuario no lo ve)
    http_response_code(500);     // código HTTP 500 = error del servidor
    exit('No se pudo conectar con la base de datos.'); // muestra un mensaje simple y corta el programa
}
