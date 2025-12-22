<?php
use modelo\Usuario;
use modelo\Workspace;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $ws = new Workspace($_POST['codigo']);
    $ws->nome = $_POST['nome'];
    $ws->descricao = $_POST['descricao'] ?: null;
    $ws->cep = $_POST['cep'] ?: null;
    $ws->endereco = $_POST['endereco'] ?: null;
    $ws->numero = $_POST['numero'] ?: null;
    $ws->complemento = $_POST['complemento'] ?: null;
    $ws->bairro = $_POST['bairro'] ?: null;
    $ws->uf = $_POST['uf'] ?: null;
    $ws->cidade = $_POST['cidade'] ?: null;
    $ws->save();
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);