<?php

use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil('MASTER', 'ADMIN');
    if ($_GET['codigo'] == '1') {
        throw new Exception('Este cadastro não pode ser excluído.');
    }
    $usuario = new Usuario($_GET['codigo']);
    if ($usuario->perfil == Usuario::PERFIL_MASTER && Aut::$perfil != Usuario::PERFIL_MASTER) {
        throw new Exception('Sem permissão para excluir cadastro de usuário MASTER.');
    }
    $usuario->exclui();
    $ret = ['erro' => false];
} catch (Exception $e) {
    $ret = ['erro' => $e->getMessage()];
}
echo json_encode($ret);
