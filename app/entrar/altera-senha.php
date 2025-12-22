<?php
use modelo\Usuario;

include '../../def.php';
try {
    $ret = ['erro' => false];
    $usuario = Aut::token($_POST['token'], true);
    Usuario::alteraSenha($usuario->email, $_POST['senha']);
    Aut::$usuario = $usuario;
    Aut::valida($usuario->codigo);
    Aut::salva();
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);