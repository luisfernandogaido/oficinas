<?php
use modelo\{Usuario, Tarefa, Projeto};
use datahora\DataHora;

include '../../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $modo = $_GET['modo'] ?? 'dia';
    $dia = $_GET['dia'] ?? null;
    $codProjeto = intval($_GET['cod_projeto'] ?? null);
    $projeto = new Projeto($codProjeto);
    Aut::filtraConta($projeto->getCodConta());
    $iniFim = DataHora::iniFim($modo, $dia);
    $ret = Tarefa::tempoGastoProjeto($codProjeto, $iniFim['ini'], $iniFim['fim']);
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);