<?php
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraLogado();
    $relaxado = true;
    if (!$relaxado) {
        if (!password_verify($_POST['old'], Aut::$usuario->senha)) {
            throw new Exception('Senha atual incorreta.');
        }
    }
    $usuario = new Usuario(Aut::$codigo);
    $usuario->senha = $_POST['senha'];
    $usuario->salva();
    Aut::$usuario = $usuario;
    Aut::salva();
    $ret = ['erro' => false];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);