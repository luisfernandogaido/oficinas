<?php

namespace modelo;

use bd\My;
use DateMalformedStringException;
use DateTime;
use UnexpectedValueException;

use function array_map;
use function number_format;
use function usort;

class OsItem
{
    public int $codigo;
    public int $codOs;
    public ?int $codExecutante = null;
    public ?int $codProduto = null;
    public ?int $codServico = null;
    public OsItemTipo $tipo;
    public string $nome;
    public string $categoria;
    public ?UnidadeMedida $unidadeMedida = null;
    public ?float $quantidade = null;
    public ?float $preco = null;
    public ?float $custo = null;
    public ?float $desconto = null;
    public ?float $subtotal = null;
    public int $ordem = 0;
    public DateTime $criacao;

    /**
     * @param int $codigo
     * @throws DateMalformedStringException
     */
    public function __construct(int $codigo)
    {
        $this->codigo = $codigo;
        if (!$codigo) {
            return;
        }
        $c = My::con();
        $query = <<< CONSTROI
            select codigo,
                   cod_os,
                   cod_executante,
                   cod_produto,
                   cod_servico,
                   tipo,
                   nome,
                   categoria,
                   unidade_medida,
                   quantidade,
                   preco,
                   custo,
                   desconto,
                   subtotal,
                   ordem,
                   criacao
            from os_itens
            where codigo = $codigo;        
        CONSTROI;
        $l = $c->query($query)->fetch_assoc();
        if (!$l) {
            throw new UnexpectedValueException('Item de os não encontrado.');
        }
        $this->codOs = $l['cod_os'];
        $this->codExecutante = $l['cod_executante'];
        $this->codProduto = $l['cod_produto'];
        $this->codServico = $l['cod_servico'];
        $this->tipo = OsItemTipo::from($l['tipo']);
        $this->nome = $l['nome'];
        $this->categoria = $l['categoria'];
        $this->unidadeMedida = UnidadeMedida::tryFrom($l['unidade_medida'] ?? '');
        $this->quantidade = $l['quantidade'];
        $this->preco = $l['preco'];
        $this->custo = $l['custo'];
        $this->desconto = $l['desconto'];
        $this->subtotal = $l['subtotal'];
        $this->ordem = $l['ordem'];
        $this->criacao = new DateTime($l['criacao']);
    }


    /**
     * @param int $codUsuario
     * @return void
     */
    public function salva(int $codUsuario): void
    {
        if ($this->codigo) {
            $this->altera();
        } else {
            $this->insere($codUsuario);
        }
    }

    /**
     * @param int $codUsuario
     * @return void
     */
    private function insere(int $codUsuario): void
    {
        $this->criacao = new DateTime();
        $c = My::con();
        $query = <<< SQL
            insert into os_itens
            (cod_os, cod_executante, cod_produto, cod_servico, tipo, nome, categoria, unidade_medida, quantidade, preco, custo,
             desconto, subtotal, ordem, criacao)
            values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        SQL;
        $c->execute_query($query, [
            $this->codOs,
            $codUsuario,
            $this->codProduto,
            $this->codServico,
            $this->tipo->value,
            $this->nome,
            $this->categoria,
            $this->unidadeMedida->value ?? null,
            $this->quantidade,
            $this->preco,
            $this->custo,
            $this->desconto,
            $this->subtotal,
            $this->ordem,
            $this->criacao->format('Y-m-d H:i:s')
        ]);
        $this->codigo = $c->insert_id;
    }

    private function altera(): void
    {
    }



    public function precoInput(): string
    {
        if ($this->preco === null) {
            return '';
        }
        return number_format($this->preco, 2, '.', '');
    }

    public static function pesquisaUnificada(int $codWorkspace, string $search): array
    {
        $trechoProduto = ['tipo' => OsItemTipo::PRODUTO->value];
        $trechoServico = ['tipo' => OsItemTipo::SERVICO->value];
        $produtos = array_map(fn($p) => [...$trechoProduto, ...$p], Produto::search($codWorkspace, $search));
        $servicos = array_map(fn($p) => [...$trechoServico, ...$p], Servico::search($codWorkspace, $search));
        $uniao = [...$produtos, ...$servicos];
        usort($uniao, function ($a, $b) {
            return $b['contador_uso'] <=> $a['contador_uso'] ?:
                $b['interno'] <=> $a['interno'] ?:
                    $a['nome'] <=> $b['nome'];
        });

        return $uniao;
    }

    public static function findUnificado(int $codWorkspace, OsItemTipo $tipo, int $codigo): Produto|Servico
    {
        if ($tipo == OsItemTipo::PRODUTO) {
            $produto = new Produto($codigo);
            $categoria = new CategoriaProduto($produto->codCategoria);
            if ($categoria->codWorkspace != $codWorkspace) {
                throw new UnexpectedValueException('Produto não pertence ao workspace');
            }
            return $produto;
        }
        $servico = new Servico($codigo);
        $categoria = new CategoriaServico($servico->codCategoria);
        if ($categoria->codWorkspace != $codWorkspace) {
            throw new UnexpectedValueException('Serviço não pertence ao workspace');
        }
        return $servico;
    }
}