<?php
use modelo\Assinatura;
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO);
    $pendente = Assinatura::pendente(Aut::$codigo, Aut::$codConta);
    $pendente->cancela();
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);