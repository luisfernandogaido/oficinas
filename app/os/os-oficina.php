<?php

use app\os\actions\Agendamento;
use modelo\CategoriaProduto;
use modelo\CategoriaServico;
use modelo\Os;
use modelo\OsItemTipo;
use modelo\OsStatus;
use modelo\Usuario;

$cliente = new Usuario($os->codCliente);
$modeloCurto = $veiculo->modeloCurto();
$filesProblema = $os->filesProblema();
$historico = $os->historico(Aut::$perfil == Usuario::PERFIL_MASTER);
$previsaoEntrada = null;
if ($os->status == OsStatus::AGENDADA) {
    $diasSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
    $agendamento = new DateTime($os->agendamento);
    $previsaoEntrada = $diasSemana[$agendamento->format('w')] . $agendamento->format(', d/m à\s H:i');
}
$tiposCodigos = array_map(function ($item) {
    $tipo = $item->tipo->value;
    $codigo = $item->codProduto;
    if ($item->tipo == OsItemTipo::SERVICO) {
        $codigo = $item->codServico;
    }
    return "$tipo-$codigo";
}, $os->itens());
$categoriasProdutos = CategoriaProduto::list($os->codWorkspace);
$categoriasServicos = CategoriaServico::list($os->codWorkspace);
$opcoesAgendamento = [
    'dias' => Agendamento::dias(),
];
include 'os-oficina.html.php';
