<?php

use app\workspaces\WorkspaceValidator;
use modelo\NivelTanque;
use modelo\Os;
use modelo\Usuario;

include '../../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);
    $os = Os::porHash($_POST["hash"]);
    WorkspaceValidator::dono($os->codWorkspace, Aut::$codigo);
    $os->daEntrada(Aut::$codigo, $_POST['km'], NivelTanque::from($_POST['nivel-tanque']));
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);

