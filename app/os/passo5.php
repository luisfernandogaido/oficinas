<?php
use math\Bytes;
use modelo\Os;
use modelo\Problema;
use modelo\Usuario;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO, Usuario::PERFIL_CLIENTE);
    $hash = $_GET['h'];
    $os = Os::porHash($hash);
    $maxSizeTotal = Os::MAX_TOTAL_ORIGINAL_SIZE_PROBLEMA;
    $maxSizeTotalH = new Bytes($maxSizeTotal)->formata();
    include "passo5.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}