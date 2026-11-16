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


$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$id) { // si no es válido, filter_input devuelve false o null
    http_response_code(400); // 400 = solicitud incorrecta
    exit('Identificador inválido. <a href="admin_usuarios.php">Volver</a>');
}

$stmt = $conexion->prepare('SELECT id, nombre, apellido, dni, email FROM usuarios WHERE id = :id AND archivado_en IS NULL');
$stmt->execute(['id' => $id]);
$usuario = $stmt->fetch();

if ($usuario === false) { // no existe o está archivado
    http_response_code(404); // 404 = no encontrado
    exit('El usuario no existe o está archivado. <a href="admin_usuarios.php">Volver</a>');
}

$errores = [];
$nombre = $usuario['nombre'];
$apellido = $usuario['apellido'];
$dni = $usuario['dni'];
$email = $usuario['email'];

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


    if (empty($errores)) {
        try {
            $sql = 'UPDATE usuarios
                    SET nombre = :nombre, apellido = :apellido, dni = :dni, email = :email
                    WHERE id = :id AND archivado_en IS NULL';
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'dni' => $dni,
                'email' => $email,
                'id' => $id
            ]);

            $_SESSION['mensaje'] = 'Datos actualizados correctamente.';
            header('Location: admin_usuarios.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { 
                $errores[] = 'El DNI o el correo electrónico ya pertenecen a otro usuario.';
            } else {
                error_log($e->getMessage());
                $errores[] = 'No se pudieron guardar los cambios.';
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
    <title>Editar usuario · Agencia D.M</title>
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
            <h2>Editar usuario</h2>

            <?php if (!empty($errores)): ?>
                <ul class="errores">
                    <?php foreach ($errores as $error): ?>
                        <li><?= escapar($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form action="editar_usuario.php?id=<?= (int) $id ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?= escapar($_SESSION['csrf_token']) ?>">

                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" maxlength="80" required value="<?= escapar($nombre) ?>">

                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" maxlength="80" required value="<?= escapar($apellido) ?>">

                <label for="dni">DNI (7 u 8 números)</label>
                <input type="text" id="dni" name="dni" maxlength="8" pattern="[0-9]{7,8}" required value="<?= escapar($dni) ?>">

                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" maxlength="254" required value="<?= escapar($email) ?>">

                <button type="submit">Guardar cambios</button>
                <a class="boton boton-gris" href="admin_usuarios.php">Cancelar</a>
            </form>
        </section>
    </main>

    <footer>
        <p>Agencia D.M · Trabajo práctico final · Instituto Sagrado Corazón de Almagro</p>
    </footer>
</body>
</html>
