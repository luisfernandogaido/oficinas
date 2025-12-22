<?php
use bd\Formatos;
use datahora\DataHora;
use modelo\Conta;
use modelo\Usuario;
use modelo\WhatsappValidacao;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER, Usuario::PERFIL_ADMIN);
    $codigo = $_GET['codigo'] ?? 0;
    $usuario = new Usuario($codigo);
    $titulo = $codigo ? 'Editar usuário' : 'Cadastrar usuário';
    $perfis = Usuario::PERFIS;
    $contas = Conta::all();
    $stati = Usuario::STATUS;
    $informarSenha = $codigo == 0;
    include "form-usuario.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}