<?php
use templates\Gaido;

include '../../def.php';
try {
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}