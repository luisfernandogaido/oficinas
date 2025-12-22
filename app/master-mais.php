<?php
use templates\Gaido;

include '../def.php';
try {
    $erro = Aut::tokenUse($_GET['token'] ?? null);
    if ($erro) {
        throw new Exception('Token inválido');
    }
    include "master-mais.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}