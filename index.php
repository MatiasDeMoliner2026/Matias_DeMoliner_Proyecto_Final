<?php
?>
<!DOCTYPE html>
<html lang="es"> <!-- lang="es": el idioma de la página es español -->
<head>
    <meta charset="UTF-8"> <!-- permite mostrar acentos y ñ correctamente -->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- para que se vea bien en el celular -->
    <title>Agencia D.M</title>
    <link rel="stylesheet" href="css/estilos.css"> <!-- enlaza el archivo de estilos -->
</head>
<body>
    <header>
        <h1 class="logo"><a href="index.php">Agencia D.M</a></h1>
        <nav>
            <a href="login.php">Ingresar</a>
            <a href="registro.php">Crear cuenta</a>
        </nav>
    </header>

    <main class="contenedor">
        <img class="banner" src="img/banner..jfif" alt="Imagen de la agencia">

        <section class="tarjeta">
            <h2>Representación de futuros jugadores</h2>
            <p>
                En Agencia D.M acompañamos a jugadores juveniles y a sus familias.
                Creá tu cuenta para ver tus datos en tu panel personal.
            </p>
            <p>
                <a class="boton" href="registro.php">Crear cuenta</a>
                <a class="boton boton-gris" href="login.php">Ya tengo cuenta</a>
            </p>
        </section>
    </main>

    <section class="tres-columnas" aria-label="Qué hacemos">
    <article class="tarjeta">
        <h2>Registro de perfiles</h2>
        <p>Cada jugador tiene su ficha personal protegida con usuario y contraseña. Solo vos y la agencia acceden a tus datos.</p>
    </article>
    <article class="tarjeta">
        <h2>Seguimiento</h2>
        <p>Los agentes administran las cuentas: consultan, buscan y actualizan la información de cada jugador en un solo lugar.</p>
    </article>
    <article class="tarjeta">
        <h2>Datos bajo control</h2>
        <p>Las cuentas no se borran: se archivan y pueden reactivarse. El historial de la agencia nunca se pierde.</p>
    </article>
</section>

    <footer>
        <p>Agencia D.M - Programación y Base De Datos - ISC - De Moliner - 2026 </p>
    </footer>
</body>
</html>
