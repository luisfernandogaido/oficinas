<?php
include '../../def.php';
try {
    $token = Aut::new($_POST['email'], null);
    $ret = ['erro' => false];
    $site = SITE;
    ob_start();
    include "reset.mail.php";
    $corpo = ob_get_clean();
    gmail(
        'nao-logado',
        $_POST['email'],
        Sistema::$nome . ': Redefinir senha',
        $corpo,
        false,
    );
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);