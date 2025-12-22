<?php
use modelo\Conta;
use modelo\Usuario;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER);
    $dominios = Usuario::dominiosEmails();
    include "dominios-emails.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}