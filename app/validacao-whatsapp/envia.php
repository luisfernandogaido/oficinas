<?php
use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_ADMIN, Usuario::PERFIL_PADRAO, Usuario::PERFIL_CLIENTE);
    $link = SITE . 'app/validacao-whatsapp/index.php';
    gmail(
        'gaido',
        'luisfernandogaido@gmail.com',
        'Validação de WhatsApp: codigo ' . Aut::$codigo,
        "Usuario: " . (Aut::$usuario->nome) . "<br><a href='$link'>$link</a>",
        false
    );
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);