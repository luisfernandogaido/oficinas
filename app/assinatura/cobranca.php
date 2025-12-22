<?php
use bd\Formatos;
use modelo\Assinatura;
use modelo\Usuario;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_PADRAO);
    $vigente = Assinatura::vigente(Aut::$codigo, Aut::$codConta);
    $pendente = Assinatura::pendente(Aut::$codigo, Aut::$codConta);
    if (!$pendente || $vigente) {
        header('Location: ../index.php');
        exit;
    }
    $nome = $pendente->nome;
    $valor = Formatos::moeda($pendente->valor);
    $invoiceUrl = $pendente->asaasInvoiceUrl;
    include "cobranca.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}