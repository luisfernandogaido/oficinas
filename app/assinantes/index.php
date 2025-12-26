<?php

use modelo\Assinatura;
use modelo\Usuario;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $vigentes = Assinatura::vigentes();
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}