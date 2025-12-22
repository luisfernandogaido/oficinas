<?php
include '../../def.php';
try {
    Aut::filtraLogado();
    $nome = $_POST['nome'];
    $usuario = Aut::$usuario;
    $usuario->nome = $nome;
    $usuario->salva();
    Aut::salva();
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);
