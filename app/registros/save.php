<?php
use modelo\Registro;

include '../../def.php';
try {
    Aut::filtraGaido();
    $codigo = $_POST['codigo'];
    $date = str_replace('T', ' ', $_POST['date']);
    $registro = new Registro($codigo);
    $registro->date = $date;
    $registro->type = $_POST['type'];
    switch ($_POST['type']) {
        case 'agua':
            $ml = $_POST['ml-custom'] ?: $_POST['ml'];
            $registro->float = $ml;
            break;
        case 'hl':
            $args = isset($_POST['exclama']) ? 'exclama' : null;
            $registro->args = $args;
            break;
        case 'barba':
        case 'cabelo':
        case 'outros':
            $registro->text = $_POST['obs'];
            break;
    }
    $registro->save();
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);