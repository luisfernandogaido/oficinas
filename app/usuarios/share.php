<?php
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_ADMIN);
    $usuario = new Usuario($_GET['codigo']);
    $nome = $usuario->nome;
    $email = $usuario->email;
    $celular = $usuario->celular;
    if ($_GET['tipo'] == 'email') {
        $token = Aut::new($email, $celular);
        ob_start();
        include "share.mail.php";
        $corpo = ob_get_clean();
        gmail(
            "$usuario->codigo $usuario->nome",
            $email,
            'Acesse ' . Sistema::$nome,
            $corpo,
            false,
        );
    } elseif ($_GET['tipo'] == 'whats' || $_GET['tipo'] == 'copylink') {
        $token = Aut::new($email, $celular);
    }
    $link = SITE . 'app/usuarios/token-use.php?token=' . $token;
    $ret = ['erro' => false, 'token' => $token, 'link' => $link];
} catch (Exception $e) {
    error_log($e);
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);