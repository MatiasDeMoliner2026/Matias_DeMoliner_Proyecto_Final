<?php

session_start(); 

require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/escapar.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $conexion->prepare('SELECT id, nombre, apellido, posicion, edad, club FROM jugadores ORDER BY apellido, nombre');
$stmt->execute();
$jugadores = $stmt->fetchAll(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jugadores · Agencia D.M</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <h1 class="logo"><a href="index.php">Agencia D.M</a></h1>
        <nav>
            <a href="panel.php">Mi panel</a> <a href="jugadores.php">Jugadores</a>
            <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
                <a href="admin_usuarios.php">Usuarios</a>
                <a href="usuarios_archivados.php">Archivados</a>
            <?php endif; ?>
            <a href="cerrar_sesion.php">Cerrar sesión</a>
        </nav>
    </header>

    <main class="contenedor">
        <section class="tarjeta">
            <h2>Jugadores</h2>
            <p>Estos son los jugadores que representa la agencia.</p>

            <?php if (count($jugadores) === 0): ?>
                <p>Todavía no hay jugadores cargados.</p>
            <?php else: ?>
                <div class="jugadores">
            
                    <?php foreach ($jugadores as $j): ?>
                        <article class="jugador">
                            <h3><?= escapar($j['nombre'] . ' ' . $j['apellido']) ?></h3>
                            <p><strong>Posición:</strong> <?= escapar($j['posicion']) ?></p>
                            <p><strong>Edad:</strong> <?= escapar($j['edad']) ?> años</p>
                            <p><strong>Club:</strong> <?= escapar($j['club']) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>Agencia D.M · Trabajo práctico final · Instituto Sagrado Corazón de Almagro</p>
    </footer>
</body>
</html>
