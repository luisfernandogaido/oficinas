<?php
use modelo\Usuario;
use modelo\WhatsappValidacao;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER);
    $usuario = new Usuario($_GET['codUsuario']);
    $usuario->marcaWhatsappValidado($_GET['validado'] == 'true');
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);