<?php
use modelo\Usuario;
use modelo\WhatsappValidacao;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER);
    $wv = new WhatsappValidacao($_POST['codigo']);
    $wv->responde($_POST['numero'], $_POST['validado'] == '1', $_POST['resposta'], $wv->codUsuario);
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);