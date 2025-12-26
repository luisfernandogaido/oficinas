<?php

use modelo\Usuario;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);
    $usuario = new Usuario(Aut::$codigo);
    Aut::filtraAssinaturaTrata();
    Aut::filtraValidacaoPendenteTrata();
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}