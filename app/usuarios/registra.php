<?php
use modelo\Usuario;

include '../../def.php';
try {
    $usuario = Usuario::porEmail($_POST['email']);
    if ($usuario->codigo != 0) {
        throw new Exception('email já cadastrado');
    }
    $_SESSION['nome'] = $_POST['nome'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['senha'] = $_POST['senha'];
    $_SESSION['celular'] = $_POST['celular'];
    $_SESSION['cpf_cnpj'] = $_POST['cpf-cnpj'];
    if (!isset($_SESSION['tokens_validacao_email'])) {
        $_SESSION['tokens_validacao_email_tentativas'] = 0;
        $_SESSION['tokens_validacao_email'] = [];
    }
    $tokenValicaoEmail = str_pad(strval(random_int(0, 999999)), 6, '0', STR_PAD_LEFT);
    $_SESSION['tokens_validacao_email'][] = $tokenValicaoEmail;
    $nomeSistema = Sistema::$nome;
    $assunto = "{$nomeSistema}: Validação de email";
    //language=html
    $corpo = <<< HTML
    <p>
        Código de verificação: <b>$tokenValicaoEmail</b>
    </p>
    HTML;
    gmail('internauta', $_POST['email'], $assunto, $corpo, false);
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);