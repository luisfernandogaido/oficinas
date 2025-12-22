<?php

use modelo\Os;
use modelo\Usuario;
use modelo\Workspace;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);
    $workspace = Workspace::porCriador(Aut::$codigo);
    $historico = ($_GET['historico'] ?? 'false') == 'true';
    $oss = Os::load($workspace->codigo, $historico, $_GET['search']);
    include "operacionais.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}