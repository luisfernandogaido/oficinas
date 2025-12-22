<?php
use app\client\gd\Gd;
use templates\Gaido;

include '../../def.php';
try {
    $gd = new Gd();
    $doc = $gd->docNew('Documento sem título', '', 'texts', Aut::$codigo, null);
    header("Location: doc.php?h=$doc->hash&edit");
} catch (Throwable $e) {
    Gaido::erro($e);
}