<?php

use app\os\OsViewModel;
use app\workspaces\WorkspaceViewModel;
use modelo\Os;
use modelo\OsItemTipo;
use modelo\OsStatus;
use modelo\Usuario;
use modelo\Workspace;

/**
 * @var Os $os
 * @var OsViewModel $osVM
 */

$osVM->buttonHome = isset($_GET['home']);
$osVM->podeAgir = $os->codCliente == Aut::$codigo;
$temTexto = !empty($os->sintomas) || !empty($os->condicoes) || !empty($os->obsCliente);
$ws = new Workspace($os->codWorkspace);
$wsVM = new WorkspaceViewModel($ws);
$dono = new Usuario($ws->codCriador);
$files = $os->filesProblema();
$produtos = 0;
$servicos = 0;
$itensProduto = [];
$itensServico = [];
foreach ($os->itens() as $item) {
    if (!$item->subtotal) {
        continue;
    }
    if ($item->tipo == OsItemTipo::PRODUTO) {
        $produtos += $item->subtotal;
        $itensProduto[] = $item;
    } else {
        $servicos += $item->subtotal;
        $itensServico[] = $item;
    }
}
$produtosH = number_format($produtos, 2, '.', '');
$servicosH = number_format($servicos, 2, '.', '');

if (
    $os->status != OsStatus::SOLICITADA &&
    $os->status != OsStatus::AGENDADA &&
    $os->status != OsStatus::ANALISE
) {
    $podeEditarProblema = false;
}
$destino = urlencode("$ws->nome, $wsVM->enderecoCompleto");
$linkMaps = "https://www.google.com/maps/dir/?api=1&destination=$destino";

include 'os-cliente.html.php';
