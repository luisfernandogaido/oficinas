<?php
use app\client\gd\Gd;
use app\client\Storage;

include '../../def.php';
try {
    $hash = $_POST['hash'];
    $gd = new Gd();
    $storage = new Storage();
    $doc = $gd->docFind($hash, Aut::$codigo);
    if ($doc->owner != Aut::$codigo) {
        throw new Exception('sem permissão para excluir este doc');
    }
    foreach ($doc->minidocs as $md){
        if($md->type != 'file'){
            continue;
        }
        $storage->fileDeleteByHash($md->file->hash);
    }
    $gd->deleteDoc($doc->hash, Aut::$codigo);
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);