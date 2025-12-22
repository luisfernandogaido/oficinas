<?php
use templates\Gaido;
use modelo\{Usuario};

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}