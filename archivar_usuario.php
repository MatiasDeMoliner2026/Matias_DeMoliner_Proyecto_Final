<?php

session_start();

require_once __DIR__ . '/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SESSION['usuario_rol'] !== 'admin') {
    http_response_code(403);
    exit('Acceso denegado.');
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // 405 = método no permitido
    exit('Método no permitido.');
}

$token = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'], $token)) {
    http_response_code(403);
    exit('Solicitud no autorizada (token inválido).');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$id) {
    $_SESSION['mensaje'] = 'Identificador inválido: no se archivó ninguna cuenta.';
    header('Location: admin_usuarios.php');
    exit;
}

if ($id === (int) $_SESSION['usuario_id']) {
    $_SESSION['mensaje'] = 'No podés archivar tu propia cuenta.';
    header('Location: admin_usuarios.php');
    exit;
}


$stmt = $conexion->prepare('UPDATE usuarios SET archivado_en = NOW() WHERE id = :id AND archivado_en IS NULL');
$stmt->execute(['id' => $id]);

if ($stmt->rowCount() === 1) {
    $_SESSION['mensaje'] = 'Usuario archivado. Sus datos se conservan y podés reactivarlo desde "Archivados".';
} else {
    $_SESSION['mensaje'] = 'No se archivó nada: el usuario no existe o ya estaba archivado.';
}

header('Location: admin_usuarios.php'); 
exit;
