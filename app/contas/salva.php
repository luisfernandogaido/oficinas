<?php
use modelo\Conta;

include '../../def.php';
try {
    Aut::filtraPerfil('MASTER');
    $conta = new Conta($_POST['codigo']);
    $conta->nome = $_POST['nome'];
    $conta->ativa = $_POST['ativa'] ?? false;
    $conta->salva();
    $ret = ['erro' => false, 'id' => $conta->codigo];
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
echo json_encode($ret);