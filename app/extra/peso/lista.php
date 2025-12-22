<?php
use modelo\Peso;
use modelo\Usuario;
use templates\Gaido;

include '../../../def.php';
try {
    $ret = Peso::lista();
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);