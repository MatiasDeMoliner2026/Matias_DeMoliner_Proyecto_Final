<?php
// cerrar_sesion.php
// Cierra la sesión del usuario y lo manda al login.

session_start();          // retomamos la sesión actual
$_SESSION = [];           // vaciamos todos los datos guardados en la sesión
session_destroy();        // destruimos la sesión
header('Location: login.php'); // volvemos a la pantalla de ingreso
exit;                     // cortamos el programa
