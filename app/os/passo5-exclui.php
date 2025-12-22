<?php
use app\client\Storage;
use modelo\Os;
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO, Usuario::PERFIL_CLIENTE);
    $hash = $_GET['hash'];
    $os = Os::porHash($hash);
    $os->fileProblemaExclui($_GET['id']);
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);