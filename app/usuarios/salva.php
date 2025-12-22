<?php

use modelo\Usuario;

include '../../def.php';
try {
    Aut::filtraPerfil('MASTER', 'ADMIN');
    $codigo = intval($_POST['codigo']);
    if ($codigo == 1) {
        if (Aut::$codigo != 1) {
            throw new Exception('Sem permissão para salvar este cadastro.');
        }
        if ($_POST['perfil'] != Usuario::PERFIL_MASTER) {
            throw new Exception('Este usuário não pode ter sua permissão reduzida.');
        }
    }
    if ($_POST['perfil'] == Usuario::PERFIL_MASTER && Aut::$perfil != Usuario::PERFIL_MASTER) {
        throw new Exception('Apenas MASTER cadastra MASTER.');
    }

    $usuario = new Usuario($codigo);
    if ($codigo && $usuario->perfil == Usuario::PERFIL_MASTER && Aut::$perfil != Usuario::PERFIL_MASTER) {
        throw new Exception('Sem permissão para salvar cadastro de usuário MASTER.');
    }
    if ($_POST['perfil'] == Usuario::PERFIL_MASTER && Aut::$perfil != Usuario::PERFIL_MASTER) {
        throw new Exception('Sem permissão para inserir cadastro de usuário MASTER.');
    }
    $usuario->nome = $_POST['nome'];
    $usuario->email = $_POST['email'];
    $usuario->celular = $_POST['celular'];
    $usuario->whatsAppValidado = $_POST['whatsapp-validado'] == '1';
    $usuario->cpfCnpj = $_POST['cpf-cnpj'];
    $usuario->perfil = $_POST['perfil'];
    if (Aut::$perfil == Usuario::PERFIL_MASTER) {
        $usuario->codConta = $_POST['cod-conta'];
    } else {
        $usuario->codConta = Aut::$codConta;
    }
    $usuario->status = $_POST['status'];
    $usuario->senha = $_POST['senha'] ?? null;
    $usuario->apelido = $_POST['apelido'];
    $usuario->salva();
    $ret = ['erro' => false, 'id' => $usuario->codigo];
} catch (Exception $e) {
    error_log($e);
    $mensagem = $e->getMessage();
    if (str_contains($mensagem, 'usuario.cpf_cnpj')) {
        $mensagem = 'CPF/CNPJ já cadastrado';
    }
    $ret = ['erro' => true, 'mensagem' => $mensagem];
}
echo json_encode($ret);
