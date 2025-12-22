<?php
use templates\Gaido;
use modelo\{Conta, Usuario};

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER);
    $contas = Conta::all();
    $contas = array_map(function ($c) {
        $c['classe_ativa'] = $c['ativa'] ? 'check checked' : 'check';
        return $c;
    }, $contas);
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}