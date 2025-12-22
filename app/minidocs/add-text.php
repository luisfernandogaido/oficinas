<?php
use app\client\gd\Gd;

include '../../def.php';
try {
    $hash = $_POST['hash'];
    $gd = new Gd();
    $doc = $gd->docFind($hash, Aut::$codigo);
    if ($doc->owner != Aut::$codigo) {
        throw new Exception('sem permissão para adicionar texto neste doc');
    }
    $md = $gd->minDocNewText($doc->hash, Aut::$codigo, $_POST['text'], $_POST['idSel']);
    include 'add-text.html.php';
} catch (Throwable $e) {
    error_log($e);
    echo '<div class="error">' . e($e->getMessage()) . '</div>';
}