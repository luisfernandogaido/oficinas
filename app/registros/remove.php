<?php
use modelo\Registro;

include '../../def.php';
try {
    Aut::filtraGaido();
    $codigo = $_POST['codigo'];
    $registro = new Registro($codigo);
    $registro->delete();
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);