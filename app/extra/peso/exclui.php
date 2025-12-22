<?php
use modelo\Peso;
use modelo\Usuario;
use templates\Gaido;

include '../../../def.php';
try {
    $peso = new Peso($_GET['codigo']);
    $peso->exclui();
    $ret = ['erro' => false];
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);