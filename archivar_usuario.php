<?php
// archivar_usuario.php
// Archiva un usuario (borrado lógico): NO borra el registro, solo guarda la fecha en archivado_en.
// No muestra nada: procesa la acción y vuelve al listado.

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

// Esta acción solo se acepta por POST (desde el botón del listado), nunca desde un enlace
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // 405 = método no permitido
    exit('Método no permitido.');
}

// Token CSRF: comprueba que el formulario salió de nuestro sitio
$token = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'], $token)) {
    http_response_code(403);
    exit('Solicitud no autorizada (token inválido).');
}

// Validamos que el id sea un entero válido
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$id) {
    $_SESSION['mensaje'] = 'Identificador inválido: no se archivó ninguna cuenta.';
    header('Location: admin_usuarios.php');
    exit;
}

// Un administrador no puede archivar su propia cuenta (se quedaría sin acceso)
if ($id === (int) $_SESSION['usuario_id']) {
    $_SESSION['mensaje'] = 'No podés archivar tu propia cuenta.';
    header('Location: admin_usuarios.php');
    exit;
}

// UPDATE: pone la fecha y hora actual en archivado_en (solo si todavía estaba activo)
$stmt = $conexion->prepare('UPDATE usuarios SET archivado_en = NOW() WHERE id = :id AND archivado_en IS NULL');
$stmt->execute(['id' => $id]);

// rowCount() dice cuántas filas se modificaron: 1 = se archivó; 0 = no existía o ya estaba archivado
if ($stmt->rowCount() === 1) {
    $_SESSION['mensaje'] = 'Usuario archivado. Sus datos se conservan y podés reactivarlo desde "Archivados".';
} else {
    $_SESSION['mensaje'] = 'No se archivó nada: el usuario no existe o ya estaba archivado.';
}

header('Location: admin_usuarios.php'); // volvemos al listado
exit;
