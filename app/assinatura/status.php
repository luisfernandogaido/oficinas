<?php
use modelo\Assinatura;
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO);
    $vigente = Assinatura::vigente(Aut::$codigo, Aut::$codConta);
    $pendente = Assinatura::pendente(Aut::$codigo, Aut::$codConta);
    if (!$pendente || $vigente) {
        throw new Exception('não é preciso verificar status de cobrança');
    }
    $statusOld = $pendente->status;
    $pendente->atualizaStatus();
    $statusNew = $pendente->status;
    if ($statusOld != $statusNew) {
        Aut::$assinatura = $pendente;
        Aut::salva();
    }
    $ret = ['status' => $pendente->status];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);