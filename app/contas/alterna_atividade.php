<?php
use modelo\Conta;

include '../../def.php';
try {
    Aut::filtraPerfil('MASTER');
    $conta = new Conta($_GET['codigo']);
    $conta->ativa = $_GET['ativa'] ?? false;
    $conta->salva();
    $ret = ['erro' => false, 'ativa' => $conta->ativa];
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
echo json_encode($ret);