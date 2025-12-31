<?php

use modelo\Os;
use modelo\Usuario;

include '../../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER);
    $os = new Os($_GET['codigo']);
    $os->exclui();
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);

