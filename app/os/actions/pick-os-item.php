<?php

use app\workspaces\WorkspaceValidator;
use modelo\CategoriaProduto;
use modelo\CategoriaServico;
use modelo\Os;
use modelo\OsItem;
use modelo\OsItemTipo;
use modelo\Usuario;

include '../../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);
    $os = Os::porHash($_POST["hash"]);
    WorkspaceValidator::dono($os->codWorkspace, Aut::$codigo);
    if ($os->orcamentoTravado) {
        throw new BadMethodCallException('não é possível adicionar ou remover itens de orçamento travado');
    }
    $tipo = OsItemTipo::from($_POST["tipo"]);
    $produtoServico = OsItem::findUnificado($os->codWorkspace, $tipo, $_POST['codigo']);
    if ($_POST['checked'] == 'true') {
        $osItem = new OsItem(0);
        $osItem->codOs = $os->codigo;
        $osItem->codExecutante = Aut::$codigo;
        $osItem->tipo = $tipo;
        $osItem->codProduto = $osItem->tipo == OsItemTipo::PRODUTO ? $_POST['codigo'] : null;
        $osItem->codServico = $osItem->tipo == OsItemTipo::SERVICO ? $_POST['codigo'] : null;
        $osItem->nome = $produtoServico->nome;
        $osItem->categoria = match ($osItem->tipo) {
            OsItemTipo::PRODUTO => new CategoriaProduto($produtoServico->codCategoria)->nome,
            OsItemTipo::SERVICO => new CategoriaServico($produtoServico->codCategoria)->nome,
        };
        $osItem->unidadeMedida = $produtoServico->unidadeMedida ?? null;
        if ($produtoServico->interno) {
            $osItem->preco = $produtoServico->preco;
        }
        $osItem->salva(Aut::$codigo);
        $produtoServico->contadorUso++;
        $produtoServico->save();
    } else {
        $os->removeItemByType($tipo, $_POST['codigo']);
        $produtoServico->contadorUso--;
        $produtoServico->save();
    }
    $os->atualizaValor();
    $ret = ['os_item' => $osItem ?? null, 'produto_servico' => $produtoServico ?? null];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);

