<?php

function escapar(string $valor): string {
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'); // ENT_QUOTES escapa comillas simples y dobles
}
