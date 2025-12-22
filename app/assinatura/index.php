<?php
use bd\Formatos;
use modelo\Assinatura;
use templates\Gaido;

include '../../def.php';
try {
    $vigente = Assinatura::vigente(Aut::$codigo, Aut::$codConta);
    $iniVigente = Formatos::dataApp($vigente->ini);
    $fimVigente = Formatos::dataApp($vigente->fim);
    $historico = Assinatura::historico(Aut::$codigo, Aut::$codConta);
    $futuras = Assinatura::futuras(Aut::$codigo, Aut::$codConta);
    $tituloFuturas = 'Futura';
    if (count($futuras) > 1) {
        $tituloFuturas = 'Futuras';
    }
    $ultimoDia = Assinatura::ultimoDia(Aut::$codigo, Aut::$codConta);
    $ultimoDiaH = Formatos::dataApp($ultimoDia);
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}