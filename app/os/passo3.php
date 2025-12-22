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
    if ($os->problema == Problema::REVISAO_TROCA_OLEO || $os->problema == Problema::FUNILARIA_PINTURA) {
        $os->frequencia = null;
        $os->saveProblem();
        header('Location: passo4.php?' . $_SERVER['QUERY_STRING']);
        exit;
    }
    include "passo3.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}