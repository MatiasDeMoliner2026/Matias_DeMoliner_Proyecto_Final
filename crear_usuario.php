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

$errores = [];
$nombre = '';
$apellido = '';
$dni = '';
$email = '';
$rol = 'usuario';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        exit('Solicitud no autorizada (token inválido).');
    }

    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $rol = $_POST['rol'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    // ----- Validaciones -----
    if ($nombre === '') {
        $errores[] = 'El nombre es obligatorio.';
    }
    if ($apellido === '') {
        $errores[] = 'El apellido es obligatorio.';
    }
    if (!preg_match('/^[0-9]{7,8}$/', $dni)) {
        $errores[] = 'El DNI debe tener 7 u 8 números.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Ingresá un correo válido.';
    }
    // in_array comprueba que el rol enviado sea uno de los dos permitidos
    if (!in_array($rol, ['usuario', 'admin'], true)) {
        $errores[] = 'El rol elegido no es válido.';
        $rol = 'usuario';
    }
    if (strlen($password) < 6) {
        $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
    }
    if ($password !== $confirmar) {
        $errores[] = 'Las contraseñas no coinciden.';
    }


    if (empty($errores)) {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = 'INSERT INTO usuarios (nombre, apellido, dni, email, password_hash, rol)
                    VALUES (:nombre, :apellido, :dni, :email, :password_hash, :rol)';
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'dni' => $dni,
                'email' => $email,
                'password_hash' => $hash,
                'rol' => $rol
            ]);


            $_SESSION['mensaje'] = 'Usuario creado correctamente.';
            header('Location: admin_usuarios.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { // DNI o correo repetido
                $errores[] = 'El DNI o el correo electrónico ya están registrados.';
            } else {
                error_log($e->getMessage());
                $errores[] = 'Ocurrió un error al guardar los datos.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuevo usuario · Agencia D.M</title>
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
        <section class="tarjeta tarjeta-form">
            <h2>Nuevo usuario</h2>

            <?php if (!empty($errores)): ?>
                <ul class="errores">
                    <?php foreach ($errores as $error): ?>
                        <li><?= escapar($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form action="crear_usuario.php" method="POST">
            
                <input type="hidden" name="csrf_token" value="<?= escapar($_SESSION['csrf_token']) ?>">

                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" maxlength="80" required value="<?= escapar($nombre) ?>">

                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" maxlength="80" required value="<?= escapar($apellido) ?>">

                <label for="dni">DNI (7 u 8 números)</label>
                <input type="text" id="dni" name="dni" maxlength="8" pattern="[0-9]{7,8}" required value="<?= escapar($dni) ?>">

                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" maxlength="254" required value="<?= escapar($email) ?>">

                <label for="rol">Rol</label>
                <select id="rol" name="rol">
                    <option value="usuario" <?= $rol === 'usuario' ? 'selected' : '' ?>>Usuario (jugador)</option>
                    <option value="admin" <?= $rol === 'admin' ? 'selected' : '' ?>>Administrador</option>
                </select>

                <label for="password">Contraseña (mínimo 6 caracteres)</label>
                <input type="password" id="password" name="password" required>

                <label for="confirmar">Repetir contraseña</label>
                <input type="password" id="confirmar" name="confirmar" required>

                <button type="submit">Crear usuario</button>
                <a class="boton boton-gris" href="admin_usuarios.php">Cancelar</a>
            </form>
        </section>
    </main>

    <footer>
        <p>Agencia D.M · Trabajo práctico final · Instituto Sagrado Corazón de Almagro</p>
    </footer>
</body>
</html>
