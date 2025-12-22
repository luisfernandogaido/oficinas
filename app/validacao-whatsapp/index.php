<?php
use modelo\Conta;
use modelo\Usuario;
use modelo\WhatsappValidacao;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER);
    $validacoes = WhatsappValidacao::aValidar();
//    dd($validacoes);
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}