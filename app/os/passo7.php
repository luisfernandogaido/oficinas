<?php
use math\Bytes;
use modelo\Configuracoes;
use modelo\Os;
use modelo\Problema;
use modelo\Usuario;
use modelo\Veiculo;
use modelo\WhatsappValidacao;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO, Usuario::PERFIL_CLIENTE);
    $hash = $_GET['h'];
    $os = Os::porHash($hash);
    $usu = new Usuario(Aut::$codigo);
    $whatsApps = [
        '5514981125091', //A03 Business
        '5514981390830', //A03 Personal
        '5514991623401', //Edge Personal
    ];
    $whatsApp = new Configuracoes()->whatsApp;
    $validado = $usu->whatsAppValidado && !WhatsappValidacao::isPendente(Aut::$codigo);
    $wv = null;
    $token = null;
    if (!$validado) {
        $wv = WhatsappValidacao::cria(Aut::$codigo, true);
        $token = $wv->token;
    }
    include "passo7.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}