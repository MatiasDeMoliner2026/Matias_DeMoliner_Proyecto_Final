<?php

session_start(); 

require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/escapar.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['usuario_rol'] !== 'admin') {
    http_response_code(403);
    exit('Acceso denegado: esta sección es solo para administradores. <a href="panel.php">Volver</a>');
}

$mensaje = $_SESSION['mensaje'] ?? ''; 
unset($_SESSION['mensaje']);       

$buscar = trim(filter_input(INPUT_GET, 'buscar') ?? '');

if ($buscar !== '') {
    
    $sql = "SELECT id, nombre, apellido, dni, email, rol, creado_en
            FROM usuarios
            WHERE archivado_en IS NULL
              AND (nombre LIKE :b1 OR apellido LIKE :b2 OR dni LIKE :b3
                   OR email LIKE :b4 OR CONCAT(nombre, ' ', apellido) LIKE :b5)
            ORDER BY apellido, nombre";
    $termino = '%' . $buscar . '%';
    $stmt = $conexion->prepare($sql);
    $stmt->execute(['b1' => $termino, 'b2' => $termino, 'b3' => $termino, 'b4' => $termino, 'b5' => $termino]);
} else {
    
    $sql = 'SELECT id, nombre, apellido, dni, email, rol, creado_en
            FROM usuarios
            WHERE archivado_en IS NULL
            ORDER BY apellido, nombre';
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
}
$usuarios = $stmt->fetchAll(); 
?>
DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuarios · Agencia D.M</title>
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
            <h2>Usuarios activos</h2>

            <?php if ($mensaje !== ''): ?>
                <p class="exito"><?= escapar($mensaje) ?></p>
            <?php endif; ?>

            <form action="admin_usuarios.php" method="POST" class="buscador">
                <input type="text" name="buscar" placeholder="Nombre, apellido, DNI o correo" value="<?= escapar($buscar) ?>">
                <button type="submit">Buscar</button>
                <?php if ($buscar !== ''): ?>
                    <a class="boton boton-gris" href="admin_usuarios.php">Limpiar</a>
              <?php endif; ?>
            </form>

     <p><?= count($usuarios) ?> usuario(s) encontrado(s).</p>

            <?php if (count($usuarios) === 0): ?>
                <p>No se encontraron usuarios.</p>
   <?php else: ?>
              <table>
                    <tr>
                        <th>ID</th><th>Nombre</th><th>Apellido</th><th>DNI</th>
                        <th>Correo</th><th>Rol</th><th>Alta</th><th>Acciones</th>
                 </tr>
            
            <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= escapar($u['id']) ?></td>
                            <td><?= escapar($u['nombre']) ?></td>
                            <td><?= escapar($u['apellido']) ?></td>
                            <td><?= escapar($u['dni']) ?></td>
                            <td><?= escapar($u['email']) ?></td>
                            <td><?= escapar($u['rol']) ?></td>
                            <td><?= escapar($u['creado_en']) ?></td>
                            <td>

                                <a class="boton boton-chico" href="editar_usuario.php?id=<?= (int) $u['id'] ?>">Editar</a>

                               
                                <form action="archivar_usuario.php" method="POST" class="en-linea"
                                      onsubmit="return confirm('¿Archivar a este usuario?');">
                                    <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= escapar($_SESSION['csrf_token']) ?>">
                                    <button type="submit" class="boton-chico boton-rojo">Archivar</button>
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
