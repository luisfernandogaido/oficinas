<?php
use app\client\gd\Gd;
use app\client\Storage;

include '../../def.php';
try {
    $hash = $_POST['hash'];
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $description = $_POST['description'];
    $tags = $_POST['tags'];
    $gd = new Gd();
    $storage = new Storage();
    $doc = $gd->docFind($hash, Aut::$codigo);
    $md = null;
    foreach ($doc->minidocs as $m) {
        if ($m->id == $id) {
            $md = $m;
            break;
        }
    }
    if (!$md) {
        throw new Exception('md não encontrado');
    }
    if ($name === '') {
        $name = $md->file->name;
    }
    $storage->edit($md->file->id, $name, $description, $tags, 0);
    $md = $gd->minDocUpdateFile($hash, $id, Aut::$codigo, $name, $description, $tags);
    include 'save-file.html.php';
} catch (Throwable $e) {
    error_log($e);
    echo '<div class="error">' . e($e->getMessage()) . '</div>';
}