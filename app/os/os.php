<?php

use app\os\OsViewModel;
use modelo\Os;
use modelo\OsStatus;
use modelo\Usuario;
use modelo\Veiculo;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO, Usuario::PERFIL_CLIENTE);
    $hash = $_GET['h'];
    $viewAs = $_GET['viewas'] ?? null;
    $os = Os::porHash($hash);
    $osVM = new OsViewModel($os);
    $veiculo = new Veiculo($os->codVeiculo ?? 0);
    if ($os->problema == null || $veiculo->codigo == 0) {
        header('Location: passo1.php?h=' . $hash);
        exit;
    }
    if (Aut::$perfil == Usuario::PERFIL_CLIENTE || $viewAs == 'client') {
        if ($os->status == OsStatus::RASCUNHO) {
            $os->mudaStatus(OsStatus::SOLICITADA, Aut::$codigo);
        }
        include 'os-cliente.php';
    } else {
        Aut::filtraAssinaturaTrata();
        Aut::filtraValidacaoPendenteTrata();
        include 'os-oficina.php';
    }
} catch (Throwable $e) {
    Gaido::erro($e);
}