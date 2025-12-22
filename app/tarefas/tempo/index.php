<?php
use templates\Gaido;
use modelo\{Usuario, Tarefa};

include '../../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $modo = 'dia';
    $dia = (new DateTime())->format('Y-m-d');
    include "index.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}