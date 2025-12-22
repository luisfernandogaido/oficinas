<?php
use modelo\Conta;
use modelo\Usuario;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER, Usuario::PERFIL_ADMIN);
    $perfis = Usuario::PERFIS;
    $perfis = array_filter($perfis, function ($perfil) {
        if (Aut::$perfil == Usuario::PERFIL_MASTER) {
            return true;
        }
        return $perfil != 'MASTER';
    });
    $contas = Conta::all();
    $master = Aut::$perfil == Usuario::PERFIL_MASTER;
    $adminPersonifica = Sistema::$adminPersonifica;
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}