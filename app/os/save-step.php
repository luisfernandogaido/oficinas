<?php

use modelo\Frequencia;
use modelo\Os;
use modelo\OsStatus;
use modelo\Problema;
use modelo\Quando;
use modelo\Usuario;
use modelo\Veiculo;
use modelo\VeiculoTipo;

include '../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO, Usuario::PERFIL_CLIENTE);
    $os = Os::porHash($_POST['hash']);
    if (isset($_POST['problema'])) {
        $os->problema = Problema::from($_POST['problema']);
    }
    if (isset($_POST['quando'])) {
        $os->quando = Quando::from($_POST['quando']);
    }
    if (isset($_POST['frequencia'])) {
        $os->frequencia = Frequencia::from($_POST['frequencia']);
    }
    if (isset($_POST['sintomas'])) {
        $os->sintomas = $_POST['sintomas'] ?: null;
    }
    if (isset($_POST['condicoes'])) {
        $os->condicoes = $_POST['condicoes'] ?: null;
    }
    if (isset($_POST['obs-cliente'])) {
        $os->obsCliente = $_POST['obs-cliente'] ?: null;
    }
    if (isset($_POST['cod-veiculo'])) {
        if ($_POST['cod-veiculo']) {
            $veiculo = new Veiculo($_POST['cod-veiculo']);
            if ($veiculo->codProprietario != Aut::$codigo) {
                throw new Exception('Veículo não pertence a este usuário.');
            }
        } else {
            //novo veículo: crie, associe ao usuário e associe à OS.
            $veiculo = new Veiculo(0);
            $veiculo->codProprietario = Aut::$codigo;
            $veiculo->tipo = VeiculoTipo::from($_POST['tipo']);
            $veiculo->marca = $_POST['marca'] ?: null;
            $veiculo->modelo = $_POST['modelo'] ?: null;
            $veiculo->ano = $_POST['ano'] ?: null;
            $veiculo->combustivel = $_POST['combustivel'] ?: null;
            $veiculo->codigoFipe = $_POST['codigo-fipe'] ?: null;
            $veiculo->valorFipe = $_POST['valor-fipe'] ?: null;
            $veiculo->idFipe = $_POST['id-fipe'] ?: null;
            $veiculo->placa = $_POST['placa'] ?: null;
            $veiculo->km = $_POST['km'] ?: 0;
        }
        $veiculo->salva();
        $os->codVeiculo = $veiculo->codigo;
        // todo também deve verificar a reputação do cliente perante a oficina. não precisa moderar os confiáveis.
        if ($os->status == OsStatus::RASCUNHO) {
            if (!Aut::isGaido() && Aut::$codPersonificador != 1) {
                notifyMe('os pendente', 'Corre lá, filhão: https://oficinas.gaido.space/app/os/index.php');
            }
            if (Aut::isGaido() || Aut::$codPersonificador == 1) {
                $os->mudaStatus(OsStatus::SOLICITADA, Aut::$codigo);
            } else {
                $os->mudaStatus(OsStatus::PENDENTE_MODERACAO, Aut::$codigo);
            }
        }
    }
    if (isset($_POST['nome'])) {
        $usuario = new Usuario(Aut::$codigo);
        $usuario->nome = $_POST['nome'];
        $usuario->salva();
        Aut::salva();
    }
    $os->saveProblem();
    $ret = [];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);