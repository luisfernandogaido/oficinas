<?php
use bd\Formatos;
use modelo\Assinatura;
use modelo\Usuario;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_PADRAO);
    if (Aut::$usuario->status == Usuario::STATUS_PROVISORIO) {
        header('Location: ../usuarios/registrar.php?from=assine');
        exit;
    }
    if (Aut::$usuario->isIncompleto()) {
        header('Location: ../usuarios/completar.php?from=assine');
        exit;
    }
    $pendente = Assinatura::pendente(Aut::$codigo, Aut::$codConta);
    if ($pendente) {
        header('Location: cobranca.php');
        exit;
    }
    notifyMe('assine: ' . Aut::$codigo, Aut::$usuario->codigo . ' ' . Aut::$usuario->nome);
    $valores = json_encode(Assinatura::VALORES);
    $ultimoDia = Assinatura::ultimoDia(Aut::$codigo, Aut::$codConta);
    $ultimoDiaH = Formatos::dataApp($ultimoDia);
    $titulo = 'Assine';
    if ($ultimoDia) {
        $titulo = "Estenda a partir de $ultimoDiaH";
    }
    $from = $_GET['from'] ?? null;
    include "assine.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}