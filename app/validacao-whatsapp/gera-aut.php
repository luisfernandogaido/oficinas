<?php
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER);
    $token = Aut::new($_GET['email'], $_GET['celular']);
    $link = SITE . 'app/usuarios/token-use.php?token=' . $token;
    $ret = ['link' => $link];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);