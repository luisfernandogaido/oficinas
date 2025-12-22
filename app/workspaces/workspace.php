<?php
use modelo\Usuario;
use modelo\Workspace;
use templates\Gaido;
use app\workspaces\WorkspaceValidator;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $codigo = $_GET['codigo'] ?? 0;
    if (!$codigo) {
        if (Aut::$perfil == Usuario::PERFIL_MASTER) {
            $ws = Workspace::generate(Aut::$codigo);
        } else {
            $ws = Workspace::getOrGenerate(Aut::$codigo);
        }
        header('location: workspace.php?codigo=' . $ws->codigo);
        exit;
    }
    $ws = new Workspace($codigo);
    if (!$ws->logo) {
        $ws->logo = "";
    }
    $podeExcluir = true;
    $titulo = 'Workspace';
    if (Aut::$perfil == Usuario::PERFIL_PADRAO) {
        WorkspaceValidator::dono($codigo, Aut::$codigo);
        $podeExcluir = false;
        $titulo = 'Informações básicas';
    }
    include "workspace.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}