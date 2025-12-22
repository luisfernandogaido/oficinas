<?php

use app\client\gd\Gd;
use app\client\Storage;

include '../../def.php';
try {
    $hash = $_POST['hash'];
    $gd = new Gd();
    $doc = $gd->docFind($hash, Aut::$codigo);
    if ($doc->owner != Aut::$codigo) {
        throw new Exception('sem permissão para upload neste doc');
    }
    $storage = new Storage();
    $files = $storage->uploadFromFiles(
        'gaido',
        Aut::$usuario->codigo,
        'minidocs',
        $hash,
        0,
        0, //1080
        0, //30
        0, //85
    );
    $ret = [
        'files' => $files,
    ];
    $mds = [];
    foreach ($files as $file) {
        $md = $gd->minDocNewFile(
            $hash,
            Aut::$codigo,
            $file['id'],
            $file['name'],
            $file['size'],
            $file['type'],
            $file['hash'],
            $file['path'],
            $file['url'],
            $file['description'],
            $file['tags'],
            $file['createdAt'],
            $file['modifiedAt'],
            $_POST['idSel']
        );
        $mds[] = $md;
    }
    include 'upload.html.php';
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}