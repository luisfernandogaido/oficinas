<?php
use templates\Gaido;

include '../../def.php';
try {
    $erro = Aut::tokenUse($_GET['token'] ?? null);
    if ($erro) {
        throw new Exception('link de acesso inválido');
    }
    header('Location: ../index.php');
} catch (Throwable $e) {
    Gaido::erro($e);
}