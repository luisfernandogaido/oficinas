<?php
use modelo\Configuracoes;
use modelo\Usuario;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER);
    $confs = new Configuracoes();
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}