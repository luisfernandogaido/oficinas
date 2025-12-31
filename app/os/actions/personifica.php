<?php

use modelo\Usuario;

include '../../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER);
    Aut::personifica($_GET['cod_criador']);
    $ret = [];
} catch (Exception $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);