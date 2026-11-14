<?php


session_start();

require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/escapar.php';

// ----- Control de acceso -----
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SESSION['usuario_rol'] !== 'admin') {
    http_response_code(403);
    exit('Acceso denegado: esta sección es solo para administradores. <a href="panel.php">Volver</a>');
}

// Mensaje que dejó otra página (se muestra una sola vez)
$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);

// Archivados = los que tienen una fecha en archivado_en (IS NOT NULL). El más reciente primero.
$sql = 'SELECT id, nombre, apellido, dni, email, rol, archivado_en
        FROM usuarios
        WHERE archivado_en IS NOT NULL
        ORDER BY archivado_en DESC';
$stmt = $conexion->prepare($sql);
$stmt->execute();
$archivados = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Archivados · Agencia D.M</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <h1 class="logo"><a href="index.php">Agencia D.M</a></h1>
        <nav>
            <a href="panel.php">Mi panel</a> <a href="jugadores.php">Jugadores</a>
            <a href="admin_usuarios.php">Usuarios</a>
            <a href="crear_usuario.php">Nuevo usuario</a>
            <a href="usuarios_archivados.php">Archivados</a>
            <a href="cerrar_sesion.php">Cerrar sesión</a>
        </nav>
    </header>

    <main class="contenedor">
        <section class="tarjeta">
            <h2>Usuarios archivados</h2>

            <?php if ($mensaje !== ''): ?>
                <p class="exito"><?= escapar($mensaje) ?></p>
            <?php endif; ?>

            <?php if (count($archivados) === 0): ?>
                <p>No hay usuarios archivados.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>ID</th><th>Nombre</th><th>Apellido</th><th>DNI</th>
                        <th>Correo</th><th>Rol</th><th>Archivado el</th><th>Acción</th>
                    </tr>
                    <?php foreach ($archivados as $u): ?>
                        <tr>
                            <td><?= escapar($u['id']) ?></td>
                            <td><?= escapar($u['nombre']) ?></td>
                            <td><?= escapar($u['apellido']) ?></td>
                            <td><?= escapar($u['dni']) ?></td>
                            <td><?= escapar($u['email']) ?></td>
                            <td><?= escapar($u['rol']) ?></td>
                            <td><?= escapar($u['archivado_en']) ?></td>
                            <td>
                                <!-- Reactivar: formulario POST con token CSRF -->
                                <form action="reactivar_usuario.php" method="POST" class="en-linea"
                                      onsubmit="return confirm('¿Reactivar a este usuario?');">
                                    <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= escapar($_SESSION['csrf_token']) ?>">
                                    <button type="submit" class="boton-chico">Reactivar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>Agencia D.M · Trabajo práctico final · Instituto Sagrado Corazón de Almagro</p>
    </footer>
</body>
</html>
