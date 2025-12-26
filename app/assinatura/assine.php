<?php

use bd\Formatos;
use modelo\Assinatura;
use modelo\Usuario;
use templates\Gaido;

include '../../def.php';
try {
    Aut::filtraPerfilTrata(Usuario::PERFIL_PADRAO);
    if (Aut::$usuario->status == Usuario::STATUS_PROVISORIO) {
        header('Location: ../usuarios/registrar.php?from=assine');
        exit;
    }
    if (Aut::$usuario->isIncompleto()) {
        header('Location: ../usuarios/completar.php?from=assine');
        exit;
    }
    $pendente = Assinatura::pendente(Aut::$codigo, Aut::$codConta);
    if ($pendente) {
        header('Location: cobranca.php');
        exit;
    }
    notifyMe('assine: ' . Aut::$codigo, Aut::$usuario->codigo . ' ' . Aut::$usuario->nome);
    $valores = json_encode(Assinatura::VALORES);
    $ultimoDia = Assinatura::ultimoDia(Aut::$codigo, Aut::$codConta);
    $ultimoDiaH = Formatos::dataApp($ultimoDia);
    $titulo = 'Assine';
    if ($ultimoDia) {
        $titulo = "Estenda a partir de $ultimoDiaH";
    }
    $from = $_GET['from'] ?? null;
    $argumentos = [
        'Se evitar o esquecimento de um único aditivo por mês, o sistema já se pagou.',
        'Ache o histórico do cliente pela placa em um segundo. Chega de revirar pilha de papel.',
        'Envie orçamentos que o cliente aprova direto pelo celular. Passe confiança.',
        'Encontre qualquer carro ou cliente pelo nome, placa ou modelo instantaneamente.',
        'Botões grandes e simples, sem complicação.',
        'O sistema é o seu silêncio. Controle a oficina sem precisar estar lá o tempo todo.',
        'Tecnologia de ponta com suporte de quem está a um café de distância de você.',
        'Cadastro em 10 segundos: Menos tempo digitando e mais tempo faturando.',
        'Dinheiro no bolso: Não deixe o lucro sumir em peças e mão de obra que você esqueceu de lançar.',
        'Aprovação em tempo real: O cliente clica, o celular toca e o serviço começa na hora.',
        'Preço de "popular": Um ano de sistema custa menos que uma única revisão básica.',
        'Fim do "disse me disse": Transparência total com o cliente. O que foi aprovado está gravado.',
        'Oficina no bolso: Acesse o histórico e veja como está o pátio de onde você estiver.',
        'Sem frescura: Apenas as ferramentas que fazem o carro entrar e sair rápido da oficina.',
        'Recupere clientes: Ache quem não aparece há meses e ofereça uma revisão em dois cliques.',
    ];
    $argumentoConvincente = $argumentos[array_rand($argumentos)];

    include "assine.html.php";
} catch (Throwable $e) {
    Gaido::erro($e);
}