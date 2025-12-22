<?php
include '../../../def.php';
try {
//    $corpo = print_r(json_decode(file_get_contents('php://input'), true), true);
    $corpo = json_decode(file_get_contents('php://input'), true);
} catch (Throwable $e) {
    $corpo = print_r($e, true);
}
gmail('gaido', 'luisfernandogaido@gmail.com', 'webhook asaas', $corpo, false);