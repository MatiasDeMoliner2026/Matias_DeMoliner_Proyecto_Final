<?php
// reactivar_usuario.php
// Reactiva una cuenta archivada: vuelve a poner archivado_en en NULL.
// No muestra nada: procesa la acción y vuelve a la pantalla anterior.

session_start();

require_once __DIR__ . '/conexion.php';

// ----- Control de acceso -----
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SESSION['usuario_rol'] !== 'admin') {
    http_response_code(403);
    exit('Acceso denegado.');
}

// Solo se acepta por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido.');
}

// Token CSRF
$token = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'], $token)) {
    http_response_code(403);
    exit('Solicitud no autorizada (token inválido).');
}

// Validamos el id
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$id) {
    $_SESSION['mensaje'] = 'Identificador inválido: no se reactivó ninguna cuenta.';
    header('Location: usuarios_archivados.php');
    exit;
}

// UPDATE: archivado_en = NULL significa "cuenta activa". Solo si estaba archivada.
$stmt = $conexion->prepare('UPDATE usuarios SET archivado_en = NULL WHERE id = :id AND archivado_en IS NOT NULL');
$stmt->execute(['id' => $id]);

if ($stmt->rowCount() === 1) {
    $_SESSION['mensaje'] = 'Usuario reactivado. Ya puede volver a iniciar sesión.';
    header('Location: admin_usuarios.php'); // lo vemos de nuevo en la lista de activos
} else {
    $_SESSION['mensaje'] = 'No se reactivó nada: el usuario no existe o ya estaba activo.';
    header('Location: usuarios_archivados.php');
}
exit;
