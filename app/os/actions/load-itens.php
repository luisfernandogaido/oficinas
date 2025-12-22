<?php

use app\workspaces\WorkspaceValidator;
use modelo\Os;
use modelo\OsItemTipo;
use modelo\Usuario;

include '../../../def.php';

try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);
    $os = Os::porHash($_GET["hash"]);
    WorkspaceValidator::dono($os->codWorkspace, Aut::$codigo);
    $itens = $os->itens();
    $produtos = [];
    $servicos = [];
    foreach ($itens as $item) {
        if ($item->tipo == OsItemTipo::PRODUTO) {
            $produtos[] = $item;
        } else {
            $servicos[] = $item;
        }
    }
    include 'itens.html.php';
} catch (Throwable $e) {
    echo '<div class="error">' . e($e->getMessage()) . '</div>';
}

