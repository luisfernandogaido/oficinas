<?php
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_ADMIN);
    $master = Aut::$perfil == Usuario::PERFIL_MASTER;
    $codConta = $master ? ($_GET['cod_conta'] ?: null) : Aut::$codConta;
    $perfil = $_GET['perfil'] ?: null;
    $search = $_GET['search'] ?: null;
    $ret = Usuario::load(
        $codConta,
        $perfil,
        $search,
        [],
    );
    if (!$master) {
        $ret = array_values(array_filter($ret, function ($u) {
            return $u['perfil'] != Usuario::PERFIL_MASTER;
        }));
    }
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);