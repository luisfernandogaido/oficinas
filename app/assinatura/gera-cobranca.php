<?php
use modelo\Assinatura;
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO);
    $periodo = str_replace(['months', 'days'], '', $_POST['periodo']);
    [$meses, $dias] = array_values(array_filter(explode(' ', $periodo), fn($e) => is_numeric($e)));
    $meses = trim($meses);
    $dias = trim($dias);
    $assinatura = Assinatura::assina(Aut::$codigo, 1, $meses, $dias, $_POST['tipo']);
    $ret = [
        'asaas_invoice_url' => $assinatura->asaasInvoiceUrl
    ];
} catch (Throwable $e) {
    dd($e);
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);