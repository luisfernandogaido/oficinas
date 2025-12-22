<?php

use app\workspaces\WorkspaceValidator;
use bd\Formatos;
use modelo\Os;
use modelo\OsStatus;
use modelo\Usuario;
use modelo\Veiculo;
use modelo\Workspace;

include '../../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);
    $os = Os::porHash($_GET["hash"]);
    WorkspaceValidator::dono($os->codWorkspace, Aut::$codigo);
    if ($os->status == OsStatus::ANALISE) {
        $os->aguardaAprovacao(Aut::$codigo);
    }
    $cliente = new Usuario($os->codCliente);
    $ws = new Workspace($os->codWorkspace);
    $veiculo = new Veiculo($os->codVeiculo);
    $modeloCurto = $veiculo->modeloCurto();
    $link = SITE . "app/os/os.php?h=$_GET[hash]";
    $telefone = Formatos::telefoneBd($cliente->celular);
    $linhas = [
        "Olá, $cliente->nome!",
        "Preparamos a estimativa inicial para o serviço no seu *$modeloCurto*.",
        "Você pode conferir os valores e detalhes no link abaixo:",
        "",
        "$link",
        "",
        "*Importante*: Como ainda não avaliamos o veículo, esta é uma prévia baseada no seu relato. " .
        "O valor final será confirmado após diagnóstico presencial.",
        "",
        "Vamos agendar para você trazer o carro?",
        "*$ws->nome*",
    ];
    if ($_GET['orcamento'] == 'true') {
        $linhas = [
            "Olá, $cliente->nome!",
            "",
            "O diagnóstico técnico do seu *$modeloCurto* foi concluído.",
            "",
            "Já levantamos todas as peças e serviços necessários para o reparo. " .
            "Você pode conferir o orçamento detalhado no link seguro abaixo:",
            "",
            "$link",
            "",
            "*Validade*: Os valores e disponibilidade de peças são garantidos por 7 dias.",
            "Aguardamos sua aprovação pelo link para iniciar o serviço imediatamente.",
            "",
            "*$ws->nome*",
        ];
    }
    $mensagem = implode("\n", $linhas);
    $ret = ['numero' => $telefone, 'mensagem' => $mensagem];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);
