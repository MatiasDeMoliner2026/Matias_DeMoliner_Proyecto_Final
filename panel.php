<?php
// panel.php
// Panel personal: muestra los datos del usuario que inició sesión.
// Es una página privada: si no hay sesión, redirige al login.

session_start(); // retoma la sesión que se creó en login.php

require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/escapar.php';

// Si no existe usuario_id en la sesión, la persona no inició sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php'); // la mandamos al login
    exit;                          // y cortamos el programa
}

// Buscamos los datos del usuario en la base (nunca pedimos la contraseña ni el hash)
$sql = 'SELECT nombre, apellido, dni, email, rol, creado_en
        FROM usuarios
        WHERE id = :id AND archivado_en IS NULL
        LIMIT 1';
$stmt = $conexion->prepare($sql);
$stmt->execute(['id' => $_SESSION['usuario_id']]); // el id sale de la sesión, no de la URL
$usuario = $stmt->fetch();

// Si no se encuentra (por ejemplo, la cuenta fue archivada), cerramos la sesión
if ($usuario === false) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi panel · Agencia D.M</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <h1 class="logo"><a href="index.php">Agencia D.M</a></h1>
        <nav>
            <a href="panel.php">Mi panel</a> <a href="jugadores.php">Jugadores</a>
            <!-- Estos enlaces solo se muestran si el rol es admin -->
            <?php if ($usuario['rol'] === 'admin'): ?>
                <a href="admin_usuarios.php">Administrar usuarios</a>
            <?php endif; ?>
            <a href="cerrar_sesion.php">Cerrar sesión</a>
        </nav>
    </header>

    <main class="contenedor">
        <section class="tarjeta">
            <img class="foto-perfil" src="img/perfil.jfif" alt="Foto de perfil">

            <h2>Hola, <?= escapar($usuario['nombre']) ?></h2>
            <h3> ¿A que jugador te gustaría representar? </h3>
            <p>Estos son tus datos personales:</p>

            <table>
                <tr><th>Nombre</th><td><?= escapar($usuario['nombre']) ?></td></tr>
                <tr><th>Apellido</th><td><?= escapar($usuario['apellido']) ?></td></tr>
                <tr><th>DNI</th><td><?= escapar($usuario['dni']) ?></td></tr>
                <tr><th>Correo electrónico</th><td><?= escapar($usuario['email']) ?></td></tr>
                <tr><th>Rol</th><td><?= escapar($usuario['rol']) ?></td></tr>
                <tr><th>Fecha de alta</th><td><?= escapar($usuario['creado_en']) ?></td></tr>
            </table>
        </section>
    </main>

    <footer>
        <p>Agencia D.M · Trabajo práctico final · Instituto Sagrado Corazón de Almagro</p>
    </footer>
</body>
</html>
