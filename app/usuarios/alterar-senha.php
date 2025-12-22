<?php
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraLogadoTrata();
    include "alterar-senha.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}