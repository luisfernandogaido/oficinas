<?php
use app\client\QRCode;
use templates\Gaido;

include '../../def.php';
try {
    (new QRCode($_GET['link']))->echo();
} catch (Throwable $e) {
    Gaido::erro($e);
}