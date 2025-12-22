<?php
use app\client\gd\Gd;

include '../../def.php';
try {
    $hash = $_POST['hash'];
    $gd = new Gd();
    $doc = $gd->docFind($hash, Aut::$codigo);
    if ($doc->owner != Aut::$codigo) {
        throw new Exception('sem permissão para editar este doc');
    }
    $doc->name = trim($_POST['name']) ?: 'Documento sem título';
    $gd->docUpdate($doc);
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);