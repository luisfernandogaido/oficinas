<?php
include '../../def.php';
try {
    $token = $_GET['token'];
    $usuario = Aut::token($token, false);
    include "redefinir.html.php";
} catch (Throwable $e) {
    error_log($e);
    templates\Gaido::erro($e);
}
