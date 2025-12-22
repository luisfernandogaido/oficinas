<?php
use modelo\Conta;

include '../../def.php';
try {
    Aut::filtraPerfil('MASTER');
    $conta = new Conta($_GET['codigo']);
    $conta->exclui();
    $ret = ['erro' => false];
} catch (\Exception $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
echo json_encode($ret);