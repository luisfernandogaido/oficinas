<?php
use modelo\Os;
use modelo\Usuario;

include '../../def.php';
try {
    $wsHash = $_GET['h'];
    Os::tryLimit($_SERVER['REMOTE_ADDR']);
    Aut::registraProvisorio(1, Usuario::PERFIL_CLIENTE);
    $os = Os::abreOuUsaAberta($wsHash, Aut::$codigo);
    $ret = ['os_hash' => $os->hash, 'codigo' => $os->codigo];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);