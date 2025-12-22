<?php
use templates\Gaido;

include '../../def.php';
try {
    $h = $_GET['h'] ?? null;
    $hashes = [
        'MRFZlKvs1aw' => [
            'quando' => '2024-03-22 10:03',
            'descri' => 'Primeirinho',
        ]
    ];
    $forbidden = false;
    if(!Aut::isGaido() && !isset($hashes[$h])){
        $forbidden = true;
    }
    include "cases.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}