<?php

include '../../def.php';
try {
    $ret = [];
    if (Aut::isGaido()) {
        $ret = conf('trello');
    }
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);