<?php
use modelo\Os;
use modelo\Problema;
use modelo\Usuario;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO, Usuario::PERFIL_CLIENTE);
    $hash = $_GET['h'];
    $os = Os::porHash($hash);
    if ($os->problema == Problema::REVISAO_TROCA_OLEO) {
        $os->quando = null;
        $os->saveProblem();
        header('Location: passo3.php?' . $_SERVER['QUERY_STRING']);
        exit;
    }
    include "passo2.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}