<?php
use modelo\Configuracoes;
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER);
    $confs = new Configuracoes();
    $confs->whatsAppValidacaoAoCriar = isset($_POST['whatsapp-validacao-ao-criar']);
    $confs->whatsApp = $_POST['whatsapp'];
    $confs->save();
    $ret = [];
} catch (Throwable $e) {
    error_log($e->getMessage());
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);