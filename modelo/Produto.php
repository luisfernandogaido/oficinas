<?php

namespace modelo;

use bd\Formatos;
use bd\My;
use DateTime;
use Exception;
use UnexpectedValueException;

use function array_filter;
use function file_get_contents;
use function implode;
use function json_decode;
use function str_replace;

class Produto
{
    public int $codigo;
    public int $codCategoria;
    public string $nome;
    public string $descricao;
    public bool $interno = false;
    public int $contadorUso = 0;
    public UnidadeMedida $unidadeMedida;
    public ?string $sku;
    public ?string $marca;
    public ?string $refFabricante;
    public float $preco;
    public float $custo;
    public int $estoque;
    public int $estoqueMinimo;
    public bool $ativo = true;
    public string $criacao;
    public string $alteracao;
    public string $indice;

    /**
     * @param int $codigo
     */
    public function __construct(int $codigo)
    {
        $this->codigo = $codigo;
        if (!$this->codigo) {
            return;
        }
        $c = My::con();
        $query = <<< SQL
            select cod_categoria,
                   nome,
                   descricao,
                   interno,
                   contador_uso,
                   unidade_medida,
                   sku,
                   marca,
                   ref_fabricante,
                   preco,
                   custo,
                   estoque,
                   estoque_minimo,
                   ativo,
                   criacao,
                   alteracao,
                   indice
            from produtos
            where codigo = $codigo
        SQL;
        $l = $c->query($query)->fetch_assoc();
        if (!$l) {
            throw new UnexpectedValueException('Produto não encontrado.');
        }
        $this->codCategoria = $l['cod_categoria'];
        $this->nome = $l['nome'];
        $this->descricao = $l['descricao'];
        $this->interno = $l['interno'] == 1;
        $this->contadorUso = $l['contador_uso'];
        $this->unidadeMedida = UnidadeMedida::from($l['unidade_medida']);
        $this->sku = $l['sku'];
        $this->marca = $l['marca'];
        $this->refFabricante = $l['ref_fabricante'];
        $this->preco = $l['preco'];
        $this->custo = $l['custo'];
        $this->estoque = $l['estoque'];
        $this->estoqueMinimo = $l['estoque_minimo'];
        $this->ativo = $l['ativo'] == 1;
        $this->criacao = $l['criacao'];
        $this->alteracao = $l['alteracao'];
        $this->indice = $l['indice'];
    }

    /**
     * @return void
     */
    public function save(): void
    {
        if ($this->codigo) {
            $this->altera();
        } else {
            $this->insere();
        }
    }

    /**
     * @return void
     */
    private function insere(): void
    {
        $c = My::con();
        $query = <<< INSERE
        insert into produtos (
            cod_categoria, nome, descricao, interno, contador_uso, unidade_medida, sku, marca,
            ref_fabricante, preco, custo, estoque, estoque_minimo, ativo, criacao, alteracao, indice
        )
        values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        INSERE;
        $com = $c->prepare($query);
        $com->execute([
            $this->codCategoria,
            $this->nome,
            $this->descricao,
            $this->interno ? 1 : 0,
            $this->contadorUso,
            $this->unidadeMedida->value,
            $this->sku,
            $this->marca,
            $this->refFabricante,
            $this->preco,
            $this->custo,
            $this->estoque,
            $this->estoqueMinimo,
            $this->ativo,
            $this->criacao,
            $this->alteracao,
            $this->indice,
        ]);
        $this->codigo = $com->insert_id;
    }

    /**
     * @return void
     */
    private function altera(): void
    {
        $c = My::con();
        $query = <<< ALTERA
        update produtos
        set interno      = ?,
            contador_uso = ?,
            preco        = ?
        where codigo = ?
        ALTERA;
        $c->execute_query($query, [$this->interno ? 1 : 0, $this->contadorUso, $this->preco, $this->codigo]);
    }

    /**
     * @param int $codWorkspace
     * @return void
     * @throws Exception
     */
    public static function generate(int $codWorkspace): void
    {
        CategoriaProduto::generate($codWorkspace);
        $categorias = CategoriaProduto::list($codWorkspace);
        $produtos = json_decode(file_get_contents('https://gaido.space/data/produtos.json'), true);
        foreach ($categorias as $categoria) {
            $produtosCategoria = array_filter($produtos, fn($produto) => $categoria['nome'] == $produto['categoria']);
            foreach ($produtosCategoria as $produtoCategoria) {
                $produto = new Produto(0);
                $produto->codCategoria = $categoria['codigo'];
                $produto->nome = $produtoCategoria['nome'];
                $produto->descricao = $produtoCategoria['descricao'];
                $produto->unidadeMedida = UnidadeMedida::from($produtoCategoria['unidade_medida']);
                $produto->sku = null;
                $produto->marca = null;
                $produto->refFabricante = null;
                $produto->preco = $produtoCategoria['preco_sugerido'];
                $produto->custo = $produtoCategoria['custo_sugerido'];
                $produto->estoque = 0;
                $produto->estoqueMinimo = 0;
                $produto->criacao = new DateTime()->format('Y-m-d H:i:s');
                $produto->alteracao = $produto->criacao;
                $produto->indice = implode(' ', [
                    $produto->nome,
                    $categoria['nome'],
                ]);
                $produto->save();
            }
        }
    }

    public static function search(int $codWorkspace, string $search): array
    {
        $search = Formatos::ft(str_replace('-', ' ', $search));
        $c = My::con();
        $where = ["c.cod_workspace = ?"];
        $param = [$codWorkspace];
        if ($search) {
            $where[] = "match(p.indice) against(? in boolean mode)";
            $param[] = $search;
        } else {
            $where[] = "p.interno = 1";
        }
        $strWhere = implode(" and ", $where);
        $query = <<< SQL
            select p.codigo, p.nome, p.descricao, p.preco, c.nome categoria, p.interno, p.contador_uso,
                   p.unidade_medida,p.estoque
            from produtos p
                 inner join categorias_produtos c on p.cod_categoria = c.codigo
            where $strWhere
            order by c.nome, p.nome
        SQL;
        $r = $c->execute_query($query, $param);
        $produtos = [];
        while ($l = $r->fetch_assoc()) {
            $produtos[] = $l;
        }
        return $produtos;
    }

}