<?php

use app\workspaces\WorkspaceValidator;
use modelo\Os;
use modelo\Usuario;

include '../../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);
    $os = Os::porHash($_GET["hash"]);
    WorkspaceValidator::dono($os->codWorkspace, Aut::$codigo);
    $os->conclui(Aut::$codigo);
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);

