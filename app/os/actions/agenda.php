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
    $os = Os::porHash($_POST["hash"]);
    WorkspaceValidator::dono($os->codWorkspace, Aut::$codigo);

    $os->agenda(Aut::$codigo, "{$_POST['data-agendamento']} {$_POST['horario']}");

    $cliente = new Usuario($os->codCliente);
    $ws = new Workspace($os->codWorkspace);
    $veiculo = new Veiculo($os->codVeiculo);
    $modeloCurto = $veiculo->modeloCurto();

    $dataAgendamento = new DateTime($_POST['data-agendamento']);
    $diasSemana = [
        'Domingo',
        'Segunda-feira',
        'Terça-feira',
        'Quarta-feira',
        'Quinta-feira',
        'Sexta-feira',
        'Sábado',
        'Domingo'
    ];
    $diaCurto = $dataAgendamento->format('d/m');
    $diaSemana = $diasSemana[$dataAgendamento->format('w')];
    $endereco = "$ws->endereco, $ws->numero - $ws->bairro";
    $data = "$diaCurto ($diaSemana)";
    $link = SITE . "app/os/os.php?h=$_POST[hash]";
    $telefone = Formatos::telefoneBd($cliente->celular);
    $linhas = [
        "Olá, $cliente->nome!",
        "",
        "O agendamento para o *$modeloCurto* foi registrado em nosso sistema.",
        "",
        "*Data: $data*",
        "*Horário*: {$_POST['horario']}",
        "_{$endereco}_",
        "",
        "Qualquer imprevisto, é só nos avisar por aqui.",
        "",
        "Acompanhe: {$link}",
    ];
    $mensagem = implode("\n", $linhas);
    $ret = ['telefone' => $telefone, 'mensagem' => $mensagem];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);

