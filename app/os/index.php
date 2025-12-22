<?php
use modelo\Os;
use modelo\OsStatus;
use modelo\Usuario;
use modelo\Workspace;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}