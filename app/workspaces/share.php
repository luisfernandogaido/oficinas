<?php
use modelo\Usuario;
use modelo\Workspace;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_PADRAO);
    $ws = Workspace::porCriador(Aut::$codigo);
    $link = SITE. 'app/oficina/index.php?h='.$ws->hash;
    include "share.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}