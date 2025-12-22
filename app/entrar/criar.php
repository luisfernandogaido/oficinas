<?php
use modelo\Usuario;

include '../../def.php';
if (Sistema::$temaProfinanc) {
    header('Location: index.php?erro=Criação de contas desabilitada neste momento.');
    exit;
}
$provisorio = Aut::logado() && Aut::$usuario->status == Usuario::STATUS_PROVISORIO;
$titulo = 'Criar sua conta';
if ($provisorio) {
    $titulo = 'Sua conta é provisória. Registre-se para não perder os dados.';
}
$logado = Aut::logado();
include "criar.html.php";
