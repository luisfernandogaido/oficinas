<?php
use modelo\Conta;
use modelo\Usuario;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER, Usuario::PERFIL_ADMIN);
    $contas = Conta::all();
    $perfis = Usuario::PERFIS;
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}