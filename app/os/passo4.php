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

    $sintomas = true;
    $condicoes = true;
    $obs = true;
    $rotuloSintomas = 'Conte os sintomas que você notou';

    if ($os->problema == Problema::REVISAO_TROCA_OLEO) {
        $sintomas = false;
        $condicoes = false;
        $os->sintomas = null;
        $os->condicoes = null;
    } elseif ($os->problema == Problema::FUNILARIA_PINTURA) {
        $condicoes = false;
        $obs = false;
        $os->condicoes = null;
        $os->obsCliente = null;
        $rotuloSintomas = 'Descrição do dano/amassado';
    } else {
        $obs = false;
        $os->obsCliente = null;
    }
    $os->saveProblem();
    include "passo4.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}