<?php
use modelo\Assinatura;
use modelo\Usuario;

include '../../def.php';
try {
    $erro = Aut::tokenUse($_GET['token'] ?? null);
    if ($erro) {
        throw new Exception('Erro ao verificar conta de e-mail');
    }
    $usuario = Aut::$usuario;
    $usuario->status = Usuario::STATUS_ATIVO;
    $usuario->salva();
    Aut::$usuario = $usuario;
//    Assinatura::getInstanceTrial(Aut::$codigo, 0, 3);
    Aut::valida($usuario->codigo);
    header('Location: ../index.php');
} catch (Exception $e) {
    header('Location: index.php?erro=' . urldecode($e->getMessage()));
    exit;
}