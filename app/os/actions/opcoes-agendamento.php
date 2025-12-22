<?php

use app\os\actions\Agendamento;
use modelo\Usuario;

include '../../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);
    $ret = [
        'dias' => Agendamento::dias(),
        'horarios' => Agendamento::horarios(),
    ];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);

