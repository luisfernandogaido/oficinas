<?php
use app\workspaces\WorkspaceValidator;
use modelo\Usuario;
use modelo\Workspace;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $codigo = $_GET['codigo'];
    $ws = new Workspace($codigo);
    if (Aut::$perfil == Usuario::PERFIL_PADRAO) {
        WorkspaceValidator::dono($codigo, Aut::$codigo);
    }
    $ws->generateProductsServices();
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);