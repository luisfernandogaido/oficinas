<?php
use templates\Gaido;
use modelo\{Usuario, Conta};

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_MASTER, Usuario::PERFIL_ADMIN);
    $codigo = intval($_GET['codigo'] ?? null);
    $perfis = Usuario::PERFIS;
    $perfis = array_filter($perfis, function ($perfil) {
        if (Aut::$perfil == Usuario::PERFIL_MASTER) {
            return true;
        }
        return $perfil != 'MASTER';
    });
    $perfis = array_filter($perfis, function ($p) {
        return Aut::isGaido() || $p != 'PADRÃO';
    });
    $stati = Usuario::STATUS;
    $contas = Conta::all();
    $contas = array_filter($contas, function ($conta) {
        if (Aut::$perfil == Usuario::PERFIL_MASTER) {
            return true;
        }
        return Aut::$codConta == $conta['codigo'];
    });
    $titulo = 'Novo usuário';
    $perfil = null;
    $codConta = Aut::$codConta;
    $status = null;
    $nome = null;
    $email = null;
    $celular = null;
    $whatsappValidado = null;
    $cpfCnpj = null;
    $nomeUsuario = null;
    $apelido = null;
    if ($codigo) {
        $codigo = intval($_GET['codigo']);
        $titulo = 'Alterar usuário';
        $usuario = new Usuario($codigo);
        if ($usuario->perfil == Usuario::PERFIL_MASTER && Aut::$perfil != Usuario::PERFIL_MASTER) {
            throw new Exception('Sem permissão para acessar cadastro de MASTER.');
        }
        Aut::filtraConta($usuario->codConta);
        $codConta = $usuario->codConta;
        $nome = $usuario->nome;
        $email = $usuario->email;
        $celular = $usuario->celular;
        $whatsappValidado = $usuario->whatsAppValidado;
        $cpfCnpj = $usuario->cpfCnpj;
        $perfil = $usuario->perfil;
        $status = $usuario->status ?: Usuario::STATUS_ATIVO;
        $apelido = $usuario->apelido;
    }
    include "usuario.html.php";
} catch (Throwable $e) {
    error_log($e);
    Gaido::erro($e);
}