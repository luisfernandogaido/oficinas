<?php

use app\os\OsViewModel;
use modelo\Os;
use modelo\OsItemTipo;
use modelo\Usuario;

include '../../../def.php';

try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);
    $os = Os::porHash($_GET["hash"]);
    $osVM = new OsViewModel($os);
    $totalProdutos = 0;
    $totalServicos = 0;
    foreach ($os->itens() as $item) {
        if ($item->tipo == OsItemTipo::PRODUTO) {
            $totalProdutos += $item->subtotal;
        } else {
            $totalServicos += $item->subtotal;
        }
    }
    $totalProdutosH = number_format($totalProdutos, 2, ',', '');
    $totalServicosH = number_format($totalServicos, 2, ',', '');
    include 'resumo.html.php';
} catch (Throwable $e) {
    echo '<div class="error">' . e($e->getMessage()) . '</div>';
}

