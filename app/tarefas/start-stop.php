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
    if ($_POST['acao'] == 'start') {
        $tarefa->start();
    } else {
        $tarefa->stop();
    }
    $ret = ['id' => $tarefa->getCodigo()];
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);