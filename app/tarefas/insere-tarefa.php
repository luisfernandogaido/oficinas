<?php
use modelo\Tarefa;

include '../../def.php';
try {
    Aut::filtraGaido();
    $tarefa = Tarefa::byCard($_POST['link_trello']);
    if (!$tarefa) {
        $tarefa = new Tarefa(0);
        $tarefa->setCodProjeto(intval($_POST['cod_projeto']));
        $tarefa->setNome($_POST['nome']);
        $tarefa->setDescricao('');
        $tarefa->setCards([$_POST['link_trello']]);
    } else {
        $tarefa->setCodProjeto(intval($_POST['cod_projeto']));
        $tarefa->setNome($_POST['nome']);
    }
    $tarefa->salva();
    $tarefa->start();
    $ret = [];
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);