<?php

use bd\My;
use modelo\Os;
use modelo\Usuario;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO, Usuario::PERFIL_CLIENTE);
    $hash = $_GET['h'];
    $os = Os::porHash($hash);
    $c = My::con();
    $c->query("truncate os_itens");
    $c->query("update produtos set interno = 0, contador_uso = 0, preco = 0");
    $c->query("update servicos set interno = 0, contador_uso = 0, preco = 0");
} catch (Throwable $e) {
    Gaido::erro($e);
}