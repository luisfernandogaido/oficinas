<?php

use app\client\gd\Gd;
use modelo\{Usuario};

include '../../def.php';
try {
    $blacklist = ['138.204.120.66'];
    if (in_array($_SERVER['REMOTE_ADDR'], $blacklist)) {
        throw new Exception('Hum...');
    }
    $usuario = Usuario::porEmail($_POST['email']);
    if ($usuario->codigo && $usuario->status != Usuario::STATUS_PENDENTE) {
        throw new Exception('e-mail já cadastrado.');
    }
    $provisorio = Aut::logado() && Aut::$usuario->status == Usuario::STATUS_PROVISORIO;
    if ($provisorio) {
        $usuario = Aut::$usuario;
    }
    $usuario->codConta = 1;
    $usuario->nome = $_POST['nome'];
    $usuario->email = $_POST['email'];
    $usuario->senha = $_POST['senha'];
    $usuario->perfil = Usuario::PERFIL_PADRAO;
    $usuario->status = Usuario::STATUS_PENDENTE;
    $usuario->apelido = $_POST['nome'];
    $usuario->salva();
    $token = Aut::new($_POST['email'], null);
    ob_start();
    include "email-verificacao.mail.php";
    $corpo = ob_get_clean();
    gmail(
        $usuario->nome,
        $_POST['email'],
        Sistema::$app . ': verificação de e-mail',
        $corpo,
        false,
    );
    $domain = explode('@', $usuario->email)[1];
    $disposable = new Gd()->disposable($domain);
    $alerta = $disposable ? 'COM DISPOSABLE' : '';
    gmail(
        $usuario->nome,
        'luisfernandogaido@gmail.com',
        Sistema::$app . ': conta criada ' . $alerta . ' (' . $usuario->nome . ', ' . $usuario->email . ')',
        'Sucesso!',
        false
    );
    $ret = ['erro' => false];
} catch (Exception $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);