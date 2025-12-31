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
    $conta = (new Conta($usuario->codConta))->nome;
    $diasCriacao = DataHora::sinceDays(new DateTime($usuario->criacao));
    $diasAlteracao = DataHora::sinceDays(new DateTime($usuario->alteracao));
    $primeiroNome = Formatos::primeiroNome($usuario->nome ?: $usuario->apelido);
    $mensagem = "Olá, $primeiroNome!";
    $clickToChat = null;
    if ($usuario->celular) {
        $clickToChat = 'https://wa.me/55' . Formatos::telefoneBd($usuario->celular) . '?text=' . urlencode($mensagem);
    }
    $validacaoPendente = WhatsappValidacao::isPendente($codigo);
    $forcarAssinatura = $usuario->forcarAssinatura;
    $whatsappValidado = $usuario->whatsAppValidado;
    include "usuario.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}