<?php
use modelo\Conta;
use modelo\Usuario;
use modelo\WhatsappValidacao;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER);
    $codigo = $_GET['codigo'];
    $wv = new WhatsappValidacao($codigo);
    $u = $wv->usuario();
    $usuariosCelular = [];
    if ($u->celular) {
        $usuariosCelular = WhatsappValidacao::usuariosCelular($u->celular);
    }
    include "validar.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}