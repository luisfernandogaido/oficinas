<?php
use modelo\{Usuario, Projeto};
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $projetos = Projeto::lista(Aut::$codigo, false);
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}