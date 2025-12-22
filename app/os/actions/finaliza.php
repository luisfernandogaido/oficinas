<?php

use app\workspaces\WorkspaceValidator;
use bd\Formatos;
use modelo\Os;
use modelo\Usuario;
use modelo\Veiculo;
use modelo\Workspace;

include '../../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);
    $os = Os::porHash($_GET["hash"]);
    WorkspaceValidator::dono($os->codWorkspace, Aut::$codigo);
    $os->finaliza(Aut::$codigo);
    $cliente = new Usuario($os->codCliente);
    $ws = new Workspace($os->codWorkspace);
    $veiculo = new Veiculo($os->codVeiculo);
    $modeloCurto = $veiculo->modeloCurto();
    $link = SITE . "app/os/os.php?h=$_GET[hash]";
    $telefone = Formatos::telefoneBd($cliente->celular);
    $linhas = [
        "Olá, $cliente->nome! Boas notícias.",
        "O serviço no seu *$modeloCurto* foi finalizado e já testamos tudo. Ficou 100%.",
        "Você pode conferir os valores e detalhes no link abaixo:",
        "",
        "$link",
        "",
        "Aguardamos você.",
        "*$ws->nome*",
    ];
    $mensagem = implode("\n", $linhas);
    $ret = ['numero' => $telefone, 'mensagem' => $mensagem];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);

