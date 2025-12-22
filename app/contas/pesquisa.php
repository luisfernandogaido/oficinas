<?php
include '../../def.php';
try {

    $ret = ['erro' => false];
} catch (\Exception $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);