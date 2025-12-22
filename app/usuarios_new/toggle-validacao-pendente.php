<?php
use modelo\Usuario;
use modelo\WhatsappValidacao;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER);
    if($_GET['pendente'] == 'true'){
        WhatsappValidacao::cria($_GET['codUsuario'], false);
    } else {
        WhatsappValidacao::revoga($_GET['codUsuario']);
    }
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);