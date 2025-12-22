<?php
use modelo\{Usuario, Tarefa, Projeto};

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $codigo = intval($_POST['codigo'] ?? null);
    $tarefa = new Tarefa($codigo);
    $codProjeto = $tarefa->getCodProjeto();
    $projeto = new Projeto($codProjeto);
    Aut::filtraConta($projeto->getCodConta());
    $ret = ['id' => $tarefa->getCodigo()];
    $tarefa->exclui();
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);