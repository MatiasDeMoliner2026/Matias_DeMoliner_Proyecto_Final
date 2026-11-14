<?php
// login.php
// Inicio de sesión con correo y contraseña.

session_start(); // activa las sesiones: nos permite guardar datos del usuario entre páginas

require_once __DIR__ . '/conexion.php'; // trae $conexion
require_once __DIR__ . '/escapar.php';  // trae escapar()

$error = '';  // mensaje de error (vacío si todo va bien)
$email = '';  // para volver a mostrar el correo escrito

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // solo si se envió el formulario
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Ingresá tu correo y tu contraseña.';
    } else {
        // Buscamos al usuario por correo. archivado_en IS NULL = solo cuentas activas
        $sql = 'SELECT id, nombre, password_hash, rol
                FROM usuarios
                WHERE email = :email AND archivado_en IS NULL
                LIMIT 1';
        $stmt = $conexion->prepare($sql);
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch(); // devuelve la fila, o false si no encontró nada

        // password_verify compara la contraseña escrita con el hash guardado en la base
        if ($usuario !== false && password_verify($password, $usuario['password_hash'])) {

            session_regenerate_id(true); // cambia el ID de sesión por seguridad al iniciar sesión

            // Guardamos en la sesión los datos que necesitan las otras páginas
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            // Token CSRF: un código secreto que van a llevar los formularios del panel admin
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            // Redirigimos según el rol
            if ($usuario['rol'] === 'admin') {
                header('Location: admin_usuarios.php'); // el admin va a su panel
            } else {
                header('Location: panel.php');          // el usuario común va al suyo
            }
            exit; // después de redirigir hay que cortar el programa
        } else {
            // Mismo mensaje para correo inexistente o contraseña mala (no damos pistas)
            $error = 'Correo o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingresar - Agencia D.M</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <h1 class="logo"><a href="index.php">Agencia D.M</a></h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="registro.php">Crear cuenta</a>
        </nav>
    </header>

    <main class="contenedor">
        <section class="tarjeta tarjeta-form">
            <h2>Iniciar sesión</h2>

            <?php if ($error !== ''): ?>
                <p class="error"><?= escapar($error) ?></p>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" maxlength="254" required value="<?= escapar($email) ?>">

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Ingresar</button>
            </form>

            <p class="centrado">¿Todavía no tenés cuenta? <a href="registro.php">Registrate</a></p>
        </section>
    </main>

    <footer>
        <p>Agencia D.M · Trabajo práctico final · Instituto Sagrado Corazón de Almagro</p>
    </footer>
</body>
</html>
