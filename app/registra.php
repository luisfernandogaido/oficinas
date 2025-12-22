<?php
use app\client\gd\Gd;
use modelo\Assinatura;
use modelo\Convidado;
use modelo\Convite;
use modelo\Usuario;
use templates\Gaido;

include '../def.php';
try {
    $cupom = $_GET['cupom'] ?? null;
    if (Aut::logado()) {
        header('Location: index.php');
        exit;
    }
    if (!$cupom) {
        notifyMe('registrou falhou: cupom null', $_SERVER['REMOTE_ADDR']);
        Aut::logout();
        header("Location: index.php");
        exit;
    }
    $convite = Convite::byCupom($cupom);
    if (!$convite) {
        notifyMe('registrou falhou: convite não existe: ' . $cupom, $_SERVER['REMOTE_ADDR']);
        Aut::logout();
        header("Location: index.php");
        exit;
    }
    if ($convite->expirado()) {
        notifyMe("registrou falhou: cupom $cupom expirado", $_SERVER['REMOTE_ADDR']);
        Aut::logout();
        header("Location: index.php");
        exit;
    }
    Aut::registraProvisorio($convite->codConta(), Usuario::PERFIL_PADRAO);
    Assinatura::getInstanceTrial(Aut::$codigo, 0, $convite->dias);
    $convidado = new Convidado(0);
    $convidado->codigo = Aut::$codigo;
    $convidado->codConvite = $convite->codigo;
    $convidado->insere();
    Aut::valida(Aut::$codigo);
    $ipInfo = (new Gd())->ip($_SERVER['REMOTE_ADDR']);
    $dados = [
        'convite' => $convite,
        'ip' => $ip,
        'ipInfo' => $ipInfo,
        'novo_usuario' => [
            'codigo' => Aut::$codigo,
            'nome' => (new Usuario(Aut::$codigo))->nome
        ],
        'host_usuario' => [
            'codigo' => $convite->codUsuario,
            'nome' => (new Usuario($convite->codUsuario))->nome
        ],
    ];
    $body = "<pre>" . var_export($dados, true) . "</pre>";
    notifyMe("registra.php", $body, false);
    header('Location: index.php');
} catch (Throwable $e) {
    Gaido::erro($e);
}