<?php
include '../../def.php';
try {
    $validado = Aut::loginWithWhatsapp($_GET['token']);
    $ret = ['validado' => $validado];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);