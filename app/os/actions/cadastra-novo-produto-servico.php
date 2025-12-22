<?php

use app\workspaces\WorkspaceValidator;
use modelo\CategoriaProduto;
use modelo\CategoriaServico;
use modelo\Os;
use modelo\OsItem;
use modelo\OsItemTipo;
use modelo\Produto;
use modelo\Servico;
use modelo\UnidadeMedida;
use modelo\Usuario;

include '../../../def.php';
try {
    Aut::filtraPerfil(Usuario::PERFIL_PADRAO, Usuario::PERFIL_FUNCIONARIO);
    $os = Os::porHash($_POST["hash"]);
    WorkspaceValidator::dono($os->codWorkspace, Aut::$codigo);
    $tipo = OsItemTipo::from($_POST['novo-produto-servico-tipo']);
    $tipoCodigo = null;
    if ($tipo == OsItemTipo::PRODUTO) {
        $categoriaProduto = new CategoriaProduto($_POST['categoria-produto']);
        $produto = new Produto(0);
        $produto->codCategoria = $_POST['categoria-produto'];
        $produto->nome = $_POST['nome'];
        $produto->descricao = $_POST['nome'];
        $produto->interno = true;
        $produto->contadorUso = 1;
        $produto->unidadeMedida = UnidadeMedida::UNIDADE;
        $produto->sku = null;
        $produto->marca = null;
        $produto->refFabricante = null;
        $produto->preco = 0.0;
        $produto->custo = 0.0;
        $produto->estoque = 0;
        $produto->estoqueMinimo = 0;
        $produto->criacao = new DateTime()->format('Y-m-d H:i:s');
        $produto->alteracao = new DateTime()->format('Y-m-d H:i:s');
        $produto->indice = implode(' ', [
            $produto->nome,
            $categoriaProduto->nome,
        ]);
        $produto->save();
        $osItem = new OsItem(0);
        $osItem->codOs = $os->codigo;
        $osItem->codExecutante = Aut::$codigo;
        $osItem->tipo = $tipo;
        $osItem->codProduto = $produto->codigo;
        $osItem->nome = $produto->nome;
        $osItem->categoria = $categoriaProduto->nome;
        $osItem->unidadeMedida = $produto->unidadeMedida;
        $osItem->salva(Aut::$codigo);
        $tipoCodigo = 'produto-' . $produto->codigo;
    } else {
        $categoriaServico = new CategoriaServico($_POST['categoria-servico']);
        $servico = new Servico(0);
        $servico->codCategoria = $_POST['categoria-servico'];
        $servico->nome = $_POST['nome'];
        $servico->descricao = $_POST['nome'];
        $servico->interno = true;
        $servico->contadorUso = 1;
        $servico->preco = 0.0;
        $servico->custo = 0.0;
        $servico->tempo = 0;
        $servico->criacao = new DateTime()->format('Y-m-d H:i:s');
        $servico->alteracao = new DateTime()->format('Y-m-d H:i:s');
        $servico->indice = implode(' ', [
            $servico->nome,
            $categoriaServico->nome,
        ]);
        $servico->save();
        $osItem = new OsItem(0);
        $osItem->codOs = $os->codigo;
        $osItem->codExecutante = Aut::$codigo;
        $osItem->tipo = $tipo;
        $osItem->codServico = $servico->codigo;
        $osItem->nome = $servico->nome;
        $osItem->categoria = $categoriaServico->nome;
        $osItem->salva(Aut::$codigo);
        $tipoCodigo = 'servico-' . $servico->codigo;
    }
    $ret = ['tipo_codigo' => $tipoCodigo];
} catch (Throwable $e) {
    error_log($e);
    $ret = ['erro' => $e->getMessage()];
}
printJson($ret);

