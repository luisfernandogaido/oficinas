<?php
use app\client\QRCode;
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO);
    (new QRCode($_POST['link']))->echo();
} catch (Throwable $e) {
    error_log($e);
}