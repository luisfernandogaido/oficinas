<?php
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraLogado();
    $link = $_GET['link'];
    $texto = "Espalhe " . Sistema::$nome;
    $instrucoes = false;
    include "qrcode.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}