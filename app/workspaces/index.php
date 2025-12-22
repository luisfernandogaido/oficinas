<?php
use modelo\Usuario;
use modelo\Workspace;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER);
    $wss = Workspace::list();
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}