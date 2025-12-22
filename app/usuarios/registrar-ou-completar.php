<?php
use modelo\Usuario;

include '../../def.php';
if (Aut::$usuario->status == Usuario::STATUS_PROVISORIO) {
    header('Location: registrar.php');
    exit;
}
if (Aut::$usuario->isIncompleto()) {
    header('Location: completar.php');
    exit;
}