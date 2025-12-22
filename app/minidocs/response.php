<?php
use templates\Gaido;

include '../../def.php';
try {
    $path = $_GET['ru'];
    if (SERVIDOR == 'gaido.dev') {
        $path = substr($_SERVER['REQUEST_URI'], 1);
    }
    include "response.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}