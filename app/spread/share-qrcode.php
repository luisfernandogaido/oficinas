<?php
use app\client\QRCode;
use modelo\Usuario;

include '../../def.php';
try {
    (new QRCode($_POST['link']))->echo();
} catch (Throwable $e) {
    error_log($e);
}