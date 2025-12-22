<?php
use modelo\Os;

include '../../def.php';
try {
    $codUsuarioOld = Aut::$codigo;
    $validado = Aut::loginWithWhatsapp($_GET['token']);
    $codUsuarioNew = Aut::$codigo;
    if($codUsuarioOld != $codUsuarioNew){
        $os = Os::porHash($_GET['os_hash']);
        $os->moveTo($codUsuarioNew);
    }
    $ret = ['validado' => $validado, 'cod_usuario_old' => $codUsuarioOld, 'cod_usuario_new' => $codUsuarioNew ];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);