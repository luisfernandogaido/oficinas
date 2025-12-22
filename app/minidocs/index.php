<?php
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraAssinaturaTrata();
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}