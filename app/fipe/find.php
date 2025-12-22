<?php
use modelo\Fipe;
use modelo\Usuario;

include '../../def.php';
Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO, Usuario::PERFIL_CLIENTE);
try {
    $ret = [];
    $versoes = Fipe::find($_GET['search']);
    foreach ($versoes as $versao) {
        $ret[] = [
            'text' => "$versao->marca $versao->modelo $versao->ano $versao->combustivel $versao->codigoFipe",
            'value' => $versao->codigoFipe,
            'id' => $versao->id,
            'tipo' => $versao->tipo,
            'marca' => $versao->marca,
            'modelo' => $versao->modelo,
            'ano' => $versao->ano,
            'combustivel' => $versao->combustivel,
            'codigoFipe' => $versao->codigoFipe,
            'valor' => $versao->valor,
        ];
    }
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);