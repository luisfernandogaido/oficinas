<?php
use modelo\{Projeto, Usuario};

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_PADRAO);
    $codigo = intval($_POST['codigo']);
    $projeto = new Projeto($codigo);
    if ($codigo) {
        Aut::filtraGaido();
        Aut::filtraConta($projeto->getCodConta());
        Aut::filtraUsuario(Aut::$codigo);
    } else {
        $projeto->setCodConta(Aut::$codConta);
        $projeto->setCodUsuario(Aut::$codigo);
    }
    $projeto->setNome($_POST['nome']);
    $projeto->salva();
    $ret = ['id' => $projeto->getCodigo()];
} catch (Throwable $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage()];
}
printJson($ret);