<?php

use app\workspaces\WorkspaceValidator;
use modelo\Os;
use modelo\Usuario;

include '../../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);
    $os = Os::porHash($_POST["hash"]);
    WorkspaceValidator::dono($os->codWorkspace, Aut::$codigo);
    $old = $os->desconto;
    $os->desconto = $_POST['desconto'] ?: 0.0;
    $os->atualizaDesconto();
    $ret = ['valor' => $os->valor, 'valor_h' => $os->valorH];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage(), 'desconto' => $old];
}
printJson($ret);

