<?php
use modelo\{Usuario, Tarefa};
use datahora\DataHora;

include '../../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $modo = $_GET['modo'] ?? 'dia';
    $dia = new DateTime($_GET['dia'] ?? null);
    $iniFim = DataHora::iniFim($modo, $_GET['dia'] ?? null);
    $ret = Tarefa::tempoGasto(Aut::$codigo, $iniFim['ini'], $iniFim['fim']);
//    $ret = Tarefa::tempoGasto(Aut::$codigo, '2022-06-20', '2022-07-19');
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);