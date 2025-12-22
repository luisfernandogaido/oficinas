<?php

use app\client\gd\Gd;
use modelo\Usuario;

include '../../def.php';
try {
    $sucesso = false;
    $repetir = false;
    if (in_array($_POST['codigo-validacao'], array_slice($_SESSION['tokens_validacao_email'], -3))) {
        $usuario = new Usuario(Aut::$codigo);
        $usuario->nome = $_SESSION['nome'];
        $usuario->email = $_SESSION['email'];
        $usuario->senha = $_SESSION['senha'];
        $usuario->celular = $_SESSION['celular'] ?: null;
        $usuario->cpfCnpj = $_SESSION['cpf_cnpj'] ?: null;
        $usuario->status = Usuario::STATUS_ATIVO;
        $usuario->salva();
        Aut::login($_SESSION['email']);
        $sucesso = true;
        $disposable = new Gd()->disposable($usuario->email) ? 'DISPOSABLE' : '';
        $mensagem = <<< HTML
        <p>
            <b>Código:</b> $usuario->codigo
        </p>
        <p>
            <b>Nome:</b> $usuario->nome
        </p>
        <p>
            <b>Email:</b> $usuario->email
        </p>
        HTML;
        notifyMe("Código de Validação Preenchido com sucesso! $disposable", $mensagem);
    } else {
        $_SESSION['tokens_validacao_email_tentativas']++;
        if ($_SESSION['tokens_validacao_email_tentativas'] < 3) {
            $repetir = true;
        } else {
            unset(
                $_SESSION['nome'],
                $_SESSION['email'],
                $_SESSION['senha'],
                $_SESSION['celular'],
                $_SESSION['cpf_cnpj'],
                $_SESSION['tokens_validacao_email'],
                $_SESSION['tokens_validacao_email_tentativas'],
            );
        }
    }
    $ret = ['sucesso' => $sucesso, 'repetir' => $repetir];
} catch (Throwable $e) {
    error_log($e);
    $message = $e->getMessage();
    if (str_contains($message, 'usuario.cpf_cnpj')) {
        $message = 'CPF/CNPJ já cadastrado';
    }
    $ret = ['erro' => $message];
}
printJson($ret);