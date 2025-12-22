<?php
use modelo\{Usuario, Tarefa, Projeto};

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $codigo = intval($_POST['codigo'] ?? null);
    $codProjeto = intval($_POST['cod-projeto']);
    $tarefa = new Tarefa($codigo);
    $projeto = new Projeto($codProjeto);
    Aut::filtraConta($projeto->getCodConta());
    $tarefa->setCodProjeto($codProjeto);
    $tarefa->setNome($_POST['nome']);
    $tarefa->setDescricao($_POST['descricao']);
    $tarefa->setCards($_POST['trello-card'] ?? []);
    $tarefa->salva();
    $ret = ['id' => $tarefa->getCodigo()];
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);