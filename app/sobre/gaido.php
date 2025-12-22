<?php
use templates\Gaido;

include '../../def.php';
try {
    include "gaido.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}