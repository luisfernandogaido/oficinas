<?php
use modelo\Registro;

include '../../def.php';
try {
    Aut::filtraGaido();
    $ret = match ($_GET['type']) {
        'agua' => Registro::aguas($_GET['por_dia'] == 'true'),
        'hl' => Registro::hls($_GET['hl_mode']),
        'barba' => Registro::barbas(),
        'cabelo' => Registro::cabelos(),
        'outros' => Registro::outros(),
    };
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);