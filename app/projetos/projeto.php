<?php
use modelo\{Usuario, Projeto};
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $codigo = $_GET['codigo'] ?? null;
    $titulo = 'Novo projeto';
    $nome = null;
    if ($codigo) {
        Aut::filtraUsuarioTrata(Aut::$codigo);
        $titulo = 'Alterar projeto';
        $projeto = new Projeto($codigo);
        $nome = $projeto->getNome();
    }
    include "projeto.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}