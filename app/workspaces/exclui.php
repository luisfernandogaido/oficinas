<?php
use modelo\Usuario;
use modelo\Workspace;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER);
    $ws = new Workspace($_GET['codigo']);
    $ws->remove();
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);