<?php
use app\client\gd\Gd;

include '../../def.php';
try {
    $gd = new Gd();
    $docs = $gd->docs(Aut::$codigo, $_GET['search']);
    include "list.html.php";
} catch (Throwable $e) {
    error_log($e);
    echo '<div class="error">' . e($e->getMessage()) . '</div>';
}
