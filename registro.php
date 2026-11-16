<?php

require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/escapar.php'; 

$errores = [];    
$mensaje = '';   
$nombre = '';    
$apellido = '';
$dni = '';
$email = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? ''; 
    $confirmar = $_POST['confirmar'] ?? '';

 
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
            $stmt = $conexion->prepare($sql); // prepara la consulta

            $stmt->execute([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'dni' => $dni,
                'email' => $email,
                'password_hash' => $hash,
                'rol' => 'usuario'
            ]);

            $mensaje = 'Cuenta creada correctamente. Ya podés iniciar sesión.';

            $nombre = '';
            $apellido = '';
            $dni = '';
            $email = '';
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
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
    <title>Crear cuenta · Agencia D.M</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <h1 class="logo"><a href="index.php">Agencia D.M</a></h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="login.php">Ingresar</a>
        </nav>
    </header>

    <main class="contenedor">
        <section class="tarjeta tarjeta-form">
            <h2>Crear cuenta</h2>

            <!-- Si hay mensaje de éxito, lo mostramos (escapado) -->
            <?php if ($mensaje !== ''): ?>
                <p class="exito"><?= escapar($mensaje) ?></p>
            <?php endif; ?>

            <!-- Si hay errores, los mostramos en una lista -->
            <?php if (!empty($errores)): ?>
                <ul class="errores">
                    <?php foreach ($errores as $error): ?>
                        <li><?= escapar($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

        
            <form action="registro.php" method="POST">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" maxlength="80" required value="<?= escapar($nombre) ?>">

                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" maxlength="80" required value="<?= escapar($apellido) ?>">

                <label for="dni">DNI (7 u 8 números)</label>
                <input type="text" id="dni" name="dni" maxlength="8" pattern="[0-9]{7,8}" required value="<?= escapar($dni) ?>">

                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" maxlength="254" required value="<?= escapar($email) ?>">

                <label for="password">Contraseña (mínimo 6 caracteres)</label>
                <input type="password" id="password" name="password" required>

                <label for="confirmar">Repetir contraseña</label>
                <input type="password" id="confirmar" name="confirmar" required>

                <button type="submit">Crear cuenta</button>
            </form>

            <p class="centrado">¿Ya tenés cuenta? <a href="login.php">Iniciá sesión</a></p>
        </section>
    </main>

    <footer>
        <p>Agencia D.M · Trabajo práctico final · Instituto Sagrado Corazón de Almagro</p>
    </footer>
</body>
</html>
