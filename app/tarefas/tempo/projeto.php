<?php
use datahora\DataHora;
use templates\Gaido;
use modelo\{Projeto, Usuario, Tarefa};

include '../../../def.php';
try {
    $codigo = intval($_GET['codigo']);
    $projeto = new Projeto($codigo);
    if ($codigo != 10) {
        Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    }
    $dia = $_GET['dia'] ?? null;
    $mes = (new DateTime($dia))->format('m/Y');
    $mesCorrente = (new DateTime())->format('m/Y');
    if ($mes == $mesCorrente) {
        throw new Exception('mes não foi fechado');
    }
    $iniFim = DataHora::iniFim('mes', $dia);
    $tarefas = Tarefa::tempoGastoProjeto($codigo, $iniFim['ini'], $iniFim['fim']);
    include "projeto.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}