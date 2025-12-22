<?php
use modelo\Usuario;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraLogadoTrata();
    if (Aut::provisorio()) {
        header('Location: registrar.php');
        exit;
    }
    $usuario = Aut::$usuario;
    $nome = Aut::$usuario->nome;
    $email = Aut::$usuario->email;
    $celular = Aut::$usuario->celular;
    $cpfCnpj = Aut::$usuario->cpfCnpj;
    include "conta-usuario.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}