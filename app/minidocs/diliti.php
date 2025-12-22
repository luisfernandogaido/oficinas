<?php
use app\client\gd\Gd;
use app\client\Storage;

include '../../def.php';
try {
    $hash = $_POST['hash'];
    $hashFile = $_POST['hash_file'];
    $id = $_POST['id'];
    $gd = new Gd();
    $doc = $gd->docFind($hash, Aut::$codigo);
    if ($_POST['hash_file']) {
        $achouHashFile = false;
        foreach ($doc->minidocs as $md) {
            if ($md->type == 'file' && $md->file->hash == $hashFile) {
                $achouHashFile = true;
                break;
            }
        }
        if (!$achouHashFile) {
            throw new Exception('sem permissão para excluir esse arquivo');
        }
        $storage = new Storage();
        $storage->fileDeleteByHash($_POST['hash_file']);
    }
    $gd->minDocDelete($hash, $id, Aut::$codigo);
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);