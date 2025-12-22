<?php
use modelo\Peso;
use modelo\Usuario;
use templates\Gaido;

include '../../../def.php';
try {
    $peso = new Peso(null);
    $peso->setPeso($_POST['peso']);
    $peso->salva();
    $ret = [
        'erro' => false,
        'codigo' => $peso->getCodigo(),
        'data' => date('Y-m-d H:i:s'),
        'peso' => $_POST['peso']
    ];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);