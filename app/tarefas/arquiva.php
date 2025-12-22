<?php
use modelo\{Usuario, Projeto, Tarefa};
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $codigo = intval($_GET['codigo'] ?? null);
    $tarefa = new Tarefa($codigo);
    $codProjeto = $tarefa->getCodProjeto();
    $projeto = new Projeto($codProjeto);
    Aut::filtraConta($projeto->getCodConta());
    $tarefa->arquiva();
    $ret = [];
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);