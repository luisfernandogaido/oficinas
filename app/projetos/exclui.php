<?php
use modelo\{Projeto, Usuario};

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $codigo = intval($_GET['codigo']);
    $projeto = new Projeto($codigo);
    Aut::filtraConta($projeto->getCodConta());
    $projeto->exclui();
    $ret = ['id' => $projeto->getCodigo()];
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);