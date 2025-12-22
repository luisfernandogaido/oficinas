<?php
use modelo\{Usuario, Tarefa};

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $arquivadas = intval($_GET['arquivadas'] ?? null);
    $txt = $_GET['txt'] ?? null;
    $ret = Tarefa::lista(Aut::$codigo, $arquivadas, $txt);
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);