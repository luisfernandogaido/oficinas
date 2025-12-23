<?php
use app\client\Storage;

include 'def.cli.php';
$storage = new Storage();
$filesToDelete = $storage->list(app: 'oficinas', action: 'os_problema');
foreach ($filesToDelete as $file) {
    d($file);
    $storage->fileDelete($file['id']);
}
