<?php
use math\Bytes;
use modelo\Os;
use modelo\Problema;
use modelo\Usuario;
use modelo\Veiculo;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO, Usuario::PERFIL_CLIENTE);
    $hash = $_GET['h'];
    $os = Os::porHash($hash);
    $veiculos = Veiculo::doProprietario(Aut::$codigo);
    include "passo6.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}