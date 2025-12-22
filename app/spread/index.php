<?php
use modelo\Convite;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraLogado();
    $convite = Convite::generate(Aut::$codigo, 0);
    $link = SITE . 'app/registra.php?cupom=' . $convite->cupom;
    $texto = Sistema::$nome;
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}