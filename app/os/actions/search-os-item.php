<?php

use app\workspaces\WorkspaceValidator;
use modelo\Os;
use modelo\OsItem;
use modelo\Usuario;

include '../../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);
    $os = Os::porHash($_GET["hash"]);
    WorkspaceValidator::dono($os->codWorkspace, Aut::$codigo);
    $itens = OsItem::pesquisaUnificada($os->codWorkspace, $_GET["text"]);
    $frequentes = trim($_GET["text"]) == '';
    include 'found-os-itens.html.php';
} catch (Throwable $e) {
    error_log($e);
    echo '<div class="erro">' . e($e->getMessage()) . '</div>';
}