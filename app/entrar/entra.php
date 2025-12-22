<?php
include '../../def.php';
try {
    $ret = ['erro' => false];
    Aut::login($_POST['usuario'], $_POST['senha']);
} catch (Exception $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);