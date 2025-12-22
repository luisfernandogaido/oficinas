<?php
use app\client\gd\Gd;
use templates\Gaido;

include '../../def.php';
try {
    $hash = $_GET['h'];
    $gd = new Gd();
    $doc = $gd->docFind($hash, Aut::$codigo);
    if ($doc->owner != Aut::$codigo) {
        throw new Exception('sem permissão para editar este doc');
    }
//    dd($doc);
    if(isset($_GET['edit'])){
        include "doc-edit.html.php";
    } else{
        include "doc.html.php";
    }
} catch (Throwable $e) {
    Gaido::erro($e);
}