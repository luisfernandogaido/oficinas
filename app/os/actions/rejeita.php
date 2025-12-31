<?php

use app\workspaces\WorkspaceValidator;
use bd\Formatos;
use modelo\MotivoRejeicao;
use modelo\Os;
use modelo\Usuario;
use modelo\Veiculo;
use modelo\Workspace;

include '../../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);

    $os = Os::porHash($_POST["hash"]);
    WorkspaceValidator::dono($os->codWorkspace, Aut::$codigo);
    $motivo = MotivoRejeicao::from($_POST["motivo-rejeicao"]);
    $os->rejeita(Aut::$codigo, $motivo);
    $cliente = new Usuario($os->codCliente);
    $ws = new Workspace($os->codWorkspace);
    $veiculo = new Veiculo($os->codVeiculo);
    $modeloCurto = $veiculo->modeloCurto();
    $link = SITE . "app/os/os.php?h=$_POST[hash]";
    $telefone = Formatos::telefoneBd($cliente->celular);
    $linhas = [
        "Olá, $cliente->nome! Aqui é *$ws->nome*. Recebemos sua solicitação para *$modeloCurto*.",
        "",
        $motivo->mensagemCliente(),
        "",
        "Agredecemos a preferência!",
        "",
        "Detalhes da solicitação: {$link}",
    ];
    $mensagem = implode("\n", $linhas);
    $ret = ['telefone' => $telefone, 'mensagem' => $mensagem];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);

