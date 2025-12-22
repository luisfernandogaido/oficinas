<?php
use app\client\gd\Gd;

include '../../def.php';
try {
    $hash = $_POST['hash'];
    $id = $_POST['id'];
    $text = $_POST['text'];
    $gd = new Gd();
    $md = $gd->minDocUpdateText($hash, $id, Aut::$codigo, $text);
    include 'save-text.html.php';
} catch (Throwable $e) {
    error_log($e);
    echo '<div class="error">' . e($e->getMessage()) . '</div>';
}