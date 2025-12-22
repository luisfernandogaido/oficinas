<?php
use modelo\Conta;
use modelo\Usuario;
use modelo\WhatsappValidacao;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER, Usuario::PERFIL_ADMIN, Usuario::PERFIL_PADRAO);
    $whatsApps = [
        '5514981125091', //A03 Business
        '5514981390830', //A03 Personal
        '5514991623401', //Edge Personal
    ];
//    $whatsApp = $whatsApps[rand(0, 1)];
    $whatsApp = $whatsApps[2];
    $usuario = new Usuario(Aut::$codigo);
    $validado = $usuario->whatsAppValidado && !WhatsappValidacao::isPendente(Aut::$codigo);
    $wv = null;
    $token = null;
    if (!$validado) {
        $wv = WhatsappValidacao::cria(Aut::$codigo, false);
        $token = $wv->token;
    }
    include "enviar.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}