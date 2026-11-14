<?php
// escapar.php
// Función para mostrar datos en HTML de forma segura.
// Convierte caracteres especiales (< > " ') en texto inofensivo,
// así si alguien escribe <script> en un formulario, el navegador lo muestra como texto
// y NO lo ejecuta. Esto evita ataques XSS.

function escapar(string $valor): string {
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'); // ENT_QUOTES escapa comillas simples y dobles
}
