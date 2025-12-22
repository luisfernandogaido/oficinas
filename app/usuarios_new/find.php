<?php
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_ADMIN);
    $codConta = $_GET['cod-conta'] ?: null;
    if (Aut::$perfil == Usuario::PERFIL_ADMIN) {
        $codConta = Aut::$codConta;
    }

    $data = Usuario::find(
        $_GET['search'] ?: null,
        $codConta,
        $_GET['perfil'] ?: null,
        $_GET['status'] ?? null,
        isset($_GET['whatsapp-validado'],
        ),
        $_GET['page'] ?: 0,
    );
    $count = $data['count'];
    $usuarios = $data['data'];
    $registros = null;
    if ($count == 0) {
        $registros = 'Nenhum registro';
    } elseif ($count == 1) {
        $registros = 'Um registro';
    } elseif ($count <= Usuario::PAGE_SIZE) {
        $registros = "$count registros";
    } else {
        $registros = "{$data['first']}-{$data['last']} de $count registros";
    }
    $cabeTudo = $count <= Usuario::PAGE_SIZE;
    include "find.html.php";
} catch (Throwable $e) {
    error_log($e);
    printError($e->getMessage());
}