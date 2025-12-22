<?php
use modelo\{Usuario, Projeto, Tarefa};
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $projetos = Projeto::lista(Aut::$codigo, true);
    $codigo = intval($_GET['codigo'] ?? null);
    $titulo = 'Nova tarefa';
    $codProjeto = null;
    $nome = null;
    $descricao = null;
    $isArquivada = false;
    $isStarted = 0;
    $cards = [];
    if ($codigo) {
        $tarefa = new Tarefa($codigo);
        $tempos = $tarefa->tempos();
        $titulo = 'Editar tarefa';
        $codProjeto = $tarefa->getCodProjeto();
        $projeto = new Projeto($codProjeto);
        Aut::filtraContaTrata($projeto->getCodConta());
        $nome = $tarefa->getNome();
        $descricao = $tarefa->getDescricao();
        $isArquivada = $tarefa->isArquivada();
        $isStarted = $tarefa->isStarted() ? 1 : 0;
        $cards = $tarefa->getCards();
    }
    include "tarefa.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}