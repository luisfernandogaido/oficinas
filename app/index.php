<?php
use modelo\Usuario;
use modelo\Workspace;
use templates\Gaido;

include '../def.php';
try {
    $erro = Aut::tokenUse($_GET['token'] ?? null);
    if ($erro) {
        throw new Exception('Token inválido');
    }
    $podeDivulgar = false;
    if (Aut::$perfil == Usuario::PERFIL_PADRAO) {
        $podeDivulgar = Workspace::porCriador(Aut::$codigo)->nome != null;
    }
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}