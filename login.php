<?php

session_start(); 
require_once __DIR__ . '/conexion.php'; 
require_once __DIR__ . '/escapar.php';  

$error = ''; 
$email = '';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Ingresá tu correo y tu contraseña.';
    } else {
      
        $sql = 'SELECT id, nombre, password_hash, rol
                FROM usuarios
                WHERE email = :email AND archivado_en IS NULL
                LIMIT 1';
        $stmt = $conexion->prepare($sql);
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();

        if ($usuario !== false && password_verify($password, $usuario['password_hash'])) {

            session_regenerate_id(true);

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            if ($usuario['rol'] === 'admin') {
                header('Location: admin_usuarios.php'); 
            } else {
                header('Location: panel.php');          
            }
            exit;
        } else {
        
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
