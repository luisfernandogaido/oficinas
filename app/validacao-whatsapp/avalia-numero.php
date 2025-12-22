<?php
use modelo\Usuario;
use modelo\WhatsappValidacao;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER);
    $usuariosCelular = WhatsappValidacao::usuariosCelular($_GET['numero']);
    include 'avalia-numero.html.php';
} catch (Throwable $e) {
    error_log($e);
    echo '<div class="erro">' . e($e->getMessage()) . '</div>';
}
