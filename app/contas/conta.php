<?php
use templates\Gaido;
use modelo\{Conta, Usuario};

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER);
    $codigo = $_GET['codigo'] ?? null;
    $titulo = 'Nova conta';
    if ($codigo) {
        $titulo = 'Alterar conta';
    }
    $conta = new Conta($codigo);
    $nome = $conta->nome;
    $ativa = $conta->ativa;
    include "conta.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}