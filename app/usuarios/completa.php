<?php
include '../../def.php';
try {
    $usuario = Aut::$usuario;
    $usuario->completa($_POST['celular'], $_POST['cpf-cnpj']);
    Aut::valida(Aut::$codigo);
    $ret = [];
} catch (Throwable $e) {
    $msg = $e->getMessage();
    if (str_contains($msg, "key 'usuario.cpf_cnpj'")) {
        $msg = 'CPF/CNPJ já cadastrado no sistema.';
    }
    $ret = ['erro' => $msg];
}
printJson($ret);